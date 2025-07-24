<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAnalyzeDocument;
use App\Models\Document;
use App\Models\User;
use App\Models\TextAnalysis;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Element\Text;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TextAnalysisController extends Controller
{
    public function detectAIText()
    {
        return view('vinify.index');
    }

    public function detail($textAnalysisId)
    {
        $textAnalysis = TextAnalysis::find($textAnalysisId);
        $similaritiesList = $textAnalysis->similarities;

        return view('vinify.detail', compact('textAnalysis', 'similaritiesList'));
    }

    public function analyzeFile(Request $request)
    {
        $text = $request->input('text');
        $id = $request->input('documentId');
        $document = null;

        if (!$id && $text) {
            $pdf = new Dompdf();
            $pdf->loadHtml(nl2br(e($text)));
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();

            $pdfFileName = 'document_' . time() . '.pdf';
            $pdfPath = storage_path("app/public/uploads/$pdfFileName");
            file_put_contents($pdfPath, $pdf->output());
            $fileUrl = asset("storage/uploads/$pdfFileName");

            $document = Document::create([
                'name' => $pdfFileName,
                'file_url' => $fileUrl,
                'content' => $text,
                'has_been_analyzed' => false,
                'user_id' => Auth::id(),
            ]);

            $id = $document->id; // On utilise l'ID du document créé pour l'analyse
        } elseif ($id) {
            $document = Document::find($id);
            if (!$document) {
                return response()->json(['message' => 'Document non trouvé.'], 404);
            }
        } else {
            return response()->json(['message' => 'Veuillez soumettre soit un texte soit un fichier pour l\'analyse.'], 400);
        }

        if (!$text) {
            return response()->json(['message' => 'Aucun texte fourni.'], 400);
        }

        try {
            $user = auth()->user();
            $hasSubscription = $user && $user->hasActiveSubscription();

            // if (!$hasSubscription && strlen($text) > 1000) {
            //     return response()->json([
            //         'error' => 'Vous devez souscrire à un plan pour analyser plus de 1000 caractères.'
            //     ], 403);
            // }

            // ✅ 1. Créer une entrée en base avant l’appel à l’API
            $analysis = TextAnalysis::create([
                'document_id' => $document->id,
                'user_id' => $user?->id,
                'highlighted_text' => null,
                'similarities' => null,
                'excerpted_text' => null,
                'plagiarism_percentage' => 0,
                'is_ai_generated' => false,
                'status' => 'pending',
                'error_message' => null,
            ]);

            $document->update(['has_been_analyzed' => true]);

            Log::info("Dispatching ProcessPlagiarismDetection job for TextAnalysis ID: {$analysis->id}");

            ProcessAnalyzeDocument::dispatch($analysis->id, $text);

            return response()->json([
                'message' => 'L\'analyse de votre document est en cours. Vous serez notifié lorsque tout sera fini.',
                'analysis_id' => $analysis->id,
                'document_id' => $document->id,
                'status_url' => url('/api/analysis/' . $analysis->id . '/status') // URL pour le polling
            ], 202);
        } catch (\Exception $e) {
            Log::error("Erreur lors de la soumission de l'analyse pour le document ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors du traitement de votre demande.'], 500);
        }
    }

    /**
     * Récupère le statut et les résultats d'une analyse.
     * Utilisé pour le polling côté client.
     *
     * @param TextAnalysis $analysis L'instance de l'analyse récupérée par route model binding.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnalysisStatus(TextAnalysis $analysis)
    {
        if (!$analysis) {
            return response()->json(['message' => 'Analyse introuvable.'], 404);
        }

        // if ($analysis->user_id !== Auth::id()) {
        //     return response()->json(['message' => 'Accès non autorisé à cette analyse.'], 403);
        // }

        $data = [
            'id' => $analysis->id,
            'status' => $analysis->status,
            'plagiarism_percentage' => $analysis->plagiarism_percentage,
            'is_ai_generated' => $analysis->is_ai_generated,
            'highlighted_text' => $analysis->highlighted_text,
            'similarities_details' => $analysis->similarities,
            'excerpted_text' => $analysis->excerpted_text,
            'error_message' => $analysis->error_message,
            'created_at' => $analysis->created_at,
            'updated_at' => $analysis->updated_at,
        ];
        return response()->json($data);
    }

    private function convertDocxToPdf($file, $pdfPath){
        // Charger le document Word
        $phpWord = IOFactory::load($file->getRealPath());

        // Sauvegarder temporairement en HTML
        $tempHtml = tempnam(sys_get_temp_dir(), 'docx_html_') . '.html';
        $xmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $xmlWriter->save($tempHtml);

        // Lire le HTML
        $htmlContent = file_get_contents($tempHtml);

        // Convertir en PDF avec Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Sauvegarder le dPDF
        file_put_contents($pdfPath, $dompdf->output());

        // Nettoyer le fichier HTML temporaire
        @unlink($tempHtml);
    }

    public function extractText(Request $request)
    {
        // Vérifier si un fichier a été uploadé
        if (!$request->hasFile('text')) {
            return response()->json(['error' => 'Aucun contenu fourni'], 400);
        }

        $file = $request->file('text');
        $extension = strtolower($file->getClientOriginalExtension());
        try {
            $path = $file->store('uploads', 'public');

            $fileUrl = asset('/storage/uploads/' . basename($path));
            $fileName = $file->getClientOriginalName();
            Log::info("File url : $fileUrl");
            switch ($extension) {
                case 'pdf':
                    $text = $this->extractTextFromPdf($file);
                    break;
                case 'doc':
                case 'docx':
                    $text = $this->extractTextFromWord($file);
                    // Générer le PDF
                    $pdfFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf';
                    $pdfPath = storage_path("app/public/uploads/$pdfFileName");
                    $this->convertDocxToPdf($file, $pdfPath);

                    // Générer l'URL du PDF
                    $pdfUrl = asset("storage/uploads/$pdfFileName");
                    Log::info('Pdf Url for the converted document : ' . $pdfUrl);
                    $fileUrl = $pdfUrl;
                    $fileName = $pdfFileName;
                    break;
                case 'txt':
                    $text = file_get_contents($file->getRealPath());
                    if ($text === false) {
                        throw new \Exception("Erreur lors de la lecture du fichier texte.");
                    }
                    break;
                default:
                    return response()->json(['error' => 'Format de fichier non supporté'], 400);
            }

            // Vérifier si le texte a bien été extrait
            if (empty(trim($text))) {
                return response()->json(['error' => 'Impossible d\'extraire le texte du fichier'], 500);
            }

            $document = Document::create([
                'name' => $fileName,
                'file_url' => $fileUrl,
                'content' => $text,
                'has_been_analyzed' => false,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'text' => $text,
                'file_url' =>$fileUrl,
                'document_id' => $document->id,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error in extractText" . $e->getMessage());
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Extraire le texte d'un fichier PDF.
     */
    private function extractTextFromPdf($file)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getRealPath());
        $pages = $pdf->getPages();
        $text = '';
        foreach ($pages as $page) {
            $text .= $page->getText() . "\n";
        }
        return trim($text);
    }

    /**
     * Extraire le texte d'un fichier Word (doc, docx).
     */
    private function extractTextFromWord($file)
    {
        try {
            $response = Http::attach(
                'file',
                fopen($file->getRealPath(), 'rb'),
                $file->getClientOriginalName()
            )->post('http://127.0.0.1:5000/extract-text/docx'); // ton endpoint Flask

            if ($response->successful()) {
                return trim($response->json()['text'] ?? '');
            } else {
                throw new \Exception("Erreur Flask : " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Erreur extraction DOCX : " . $e->getMessage());
            return null;
        }
    }
}
