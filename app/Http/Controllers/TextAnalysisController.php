<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\TextAnalysis;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Element\Text;
use App\Jobs\ProcessAnalyzeDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TextAnalysisController extends Controller
{
    public function index()
    {
        $analyses = TextAnalysis::where('user_id', Auth::id())->latest()->get();

        return view('vinify.analyses.index', compact('analyses'));
    }

    public function detectAIText()
    {
        return view('vinify.analyses.create');
    }

    public function show($textAnalysisId)
    {
        $textAnalysis = TextAnalysis::find($textAnalysisId);
        $similaritiesList = json_decode($textAnalysis->similarities);

        return view('vinify.analyses.show', compact('textAnalysis', 'similaritiesList'));
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

    public function analyzeDocument(Request $request)
    {
        $file = null;
        $extractedText = '';
        $originalName = '';
        $hash = '';
        $fileUrl = '';
        $path = '';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $hash = md5_file($file->getRealPath());

            $existingDocument = Document::where('file_hash', $hash)->first();

            if ($existingDocument) {
                // Document existant, on récupère le contenu et le chemin déjà stockés
                $extractedText = $existingDocument->content;
                $path = $existingDocument->file_url;
            } else {
                // C'est un nouveau document
                $extension = strtolower($file->getClientOriginalExtension());
                if ($extension === 'pdf') {
                    $extractedText = $this->extractTextFromPdf($file);
                    // On stocke le fichier original et on récupère le chemin
                    $path = $file->store('temp_uploads', 'public');
                } elseif (in_array($extension, ['doc', 'docx'])) {
                    $extractedText = $this->extractTextFromWord($file);
                    // On stocke le fichier original et on récupère le chemin pour le Job
                    // Puis on convertit le docx en PDF pour l'affichage
                    $path = $this->convertDocxToPdf($file);
                    Log::info('Converted DOCX to PDF at path: ' . $path);
                } else {
                    return response()->json(['error' => 'Format de fichier non supporté.'], 400);
                }

                if ($extractedText === null || $path === null) {
                    return response()->json(['error' => 'Erreur lors du traitement du document.'], 500);
                }
            }
        } elseif ($request->input('text')) {
            $text = $request->input('text');
            $hash = md5($text);
            $originalName = 'text-analysis-' . Str::uuid() . '.pdf';

            try {
                $pdf = new Dompdf();
                $pdf->loadHtml(nl2br(e($text)));
                $pdf->setPaper('A4', 'portrait');
                $pdf->render();

                $extractedText = $text;
                $path = "temp_uploads/$originalName";
                Storage::disk('public')->put($path, $pdf->output());
            } catch (\Exception $e) {
                Log::error("Erreur de conversion HTML vers PDF: " . $e->getMessage());
                return response()->json(['error' => 'Erreur lors de la conversion du texte en PDF.'], 500);
            }
        } else {
            return response()->json(['error' => 'Aucun fichier ni texte fourni'], 400);
        }

        // Le hash est défini pour tous les cas, on peut donc utiliser firstOrCreate
        $document = Document::firstOrCreate(
            ['file_hash' => $hash],
            [
                'name' => $originalName,
                'file_url' => $path,
                'content' => $extractedText,
                'user_id' => Auth::id(),
                'has_been_analyzed' => false,
            ]
        );

        $analysis = TextAnalysis::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'is_ai_generated' => false,
            'plagiarism_percentage' => 0,
            'highlighted_text' => null,
            'similarities' => null,
            'excerpted_text' => null,
            'error_message' => null,
        ]);

        $fullPathOnDisk = storage_path("app/public/$path");
        if (DIRECTORY_SEPARATOR === '\\') {
            $fullPathOnDisk = str_replace('/', '\\', $fullPathOnDisk);
        }
        Log::info('Normalized path: ' . $fullPathOnDisk);

        // On lance le Job avec le chemin absolu sur le disque
        ProcessAnalyzeDocument::dispatch($analysis->id, $extractedText);

        // On génère l'URL publique juste avant de la renvoyer
        $fileUrl = Storage::url($path);

        return response()->json([
            'message' => 'L\'analyse de votre document est en cours.',
            'analysis_id' => $analysis->id,
            'document_id' => $document->id,
            'text' => $extractedText,
            'file_url' => $fileUrl,
            'status_url' => url('/api/analysis/' . $analysis->id . '/status')
        ], 202);
    }
    
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
            'ai_generated_probability' => $analysis->ai_generated_probability,
            'is_ai_generated' => $analysis->is_ai_generated,
            'ai_generated_label' => $analysis->ai_generated_label,
            'highlighted_text' => $analysis->highlighted_text,
            'similarities_details' => json_decode($analysis->similarities),
            'excerpted_text' => $analysis->excerpted_text,
            'error_message' => $analysis->error_message,
            'created_at' => $analysis->created_at,
            'updated_at' => $analysis->updated_at,
        ];
        return response()->json($data);
    }

    private function convertDocxToPdf(UploadedFile $file)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $pdfPath = 'temp_uploads/' . $originalName . '_' . Str::uuid() . '.pdf';

        try {
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

            // Sauvegarder le PDF dans le stockage public de Laravel
            Storage::disk('public')->put($pdfPath, $dompdf->output());

            // Nettoyer le fichier HTML temporaire
            @unlink($tempHtml);

            return $pdfPath;
        } catch (\Exception $e) {
            Log::error("Erreur de conversion DOCX vers PDF : " . $e->getMessage());
            return null;
        }
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
            // verifier si le fichier a déjà été uploadé
            $hash = md5_file($file->getRealPath());
            $existingDocument = Document::where('file_hash', $hash)->first();

            if ($existingDocument) {
                Log::info("Document already exists with hash: $hash");
                return response()->json([
                    'text' => $existingDocument->content,
                    'file_url' => $existingDocument->file_url,
                    'document_id' => $existingDocument->id,
                ], 200);
            }

            $path = $file->store('uploads', 'public');

            $fullPathOnDisk = asset('/storage/uploads/' . basename($path));
            $fileName = $file->getClientOriginalName();
            Log::info("File url : $fullPathOnDisk");
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
                    $this->convertDocxToPdf($file);

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
                'file_hash' => $hash,
                'content' => $text,
                'has_been_analyzed' => false,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'text' => $text,
                'file_url' => $fileUrl,
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

    public function delete($textAnalysisId)
    {
        $textAnalysis = TextAnalysis::find($textAnalysisId);
        if (!$textAnalysis) {
            return back()->with('error', 'Analyse introuvable.');
        }

        // Vérifier si l'utilisateur a le droit de supprimer cette analyse
        if ($textAnalysis->user_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cette analyse.');
        }

        try {
            $textAnalysis->delete();
            return back()->with('success', 'Analyse supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression de l'analyse ID {$textAnalysis->id}: " . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression de l\'analyse.');
        }
    }
}
