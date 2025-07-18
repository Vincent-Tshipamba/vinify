<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TextAnalysis;
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
        $similaritiesList = $textAnalysis->analysis_result['similarities']['similarities'];

        return view('vinify.detail', compact('textAnalysis', 'similaritiesList'));
    }

    public function analyzeFile(Request $request)
    {
        $text = $request->input('text');

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
                'analysis_result' => null,
                'user_id' => $user?->id,
                'content' => $text,
                'is_ai_generated' => false,
                'status' => 'pending',
            ]);

            Log::info("Envoi à Flask...");
            // ✅ 2. Appel à l’API d’analyse
            $response = Http::timeout(540)->post("http://127.0.0.1:5000/check-plagiarism", ['text' => $text]);

            if ($response->failed()) {
                Log::error("Erreur API DETECTION : " . $response->body());

                $analysis->update([
                    'analysis_result' => json_encode(['error' => 'API failed']),
                    'status' => 'failed',
                    'error_message' => 'Erreur de communication avec l’API.'
                ]);

                return response()->json(['error' => 'Erreur lors de l\'analyse AI.'], 500);
            }

            $result = $response->json();
            // Log::info($result['similarities']['highlighted_text']);

            // ✅ 3. Mise à jour avec les résultats de l’API
            $analysis->update([
                'highlighted_text' => $result['similarities']['highlighted_text'],
                'analysis_result' => $result['similarities'],
                'is_ai_generated' => isset($result['ai_generated_probability']) && $result['ai_generated_probability'] > 0.6, // ou autre seuil
                'status' => 'success',
            ]);

            return response()->json([$result, $analysis]);
        } catch (\Exception $e) {
            Log::error("Erreur AI_DETECTION : " . $e->getMessage());

            if (isset($analysis)) {
                $analysis->update([
                    'analysis_result' => json_encode(['error' => $e->getMessage()]),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            return response()->json(['message' => 'Erreur interne du serveur'], 500);
        }
    }


    public function extractText(Request $request)
    {
        // Vérifier si un fichier a été uploadé
        if (!$request->hasFile('text')) {
            return response()->json(['error' => 'Aucun fichier fourni'], 400);
        }

        $file = $request->file('text');
        $extension = strtolower($file->getClientOriginalExtension()); // Convertir en minuscule
        Log::info('Extension du fichier fourni : ' . $extension);
        try {
            switch ($extension) {
                case 'pdf':
                    $text = $this->extractTextFromPdf($file);
                    break;
                case 'doc':
                case 'docx':
                    $text = $this->extractTextFromWord($file);
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

            return response()->json(['text' => $text], 200);
        } catch (\Exception $e) {
            // Log::error("$e");
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
