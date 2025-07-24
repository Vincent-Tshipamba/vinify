<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\TextAnalysis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Queue\Queueable;
use App\Events\PlagiarismAnalysisCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAnalyzeDocument implements ShouldQueue
{
    use Queueable;
    protected $text_analysis_id;
    protected $content;

    /**
     * Create a new job instance.
     */
    public function __construct($text_analysis_id, $content)
    {
        $this->text_analysis_id = $text_analysis_id;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $analysis = TextAnalysis::find($this->text_analysis_id);

        if (!$analysis) {
            Log::error("TextAnalysis ID {$this->text_analysis_id} non trouvé pour l'analyse de plagiat.");
            return;
        }

        $analysis->update(['status' => 'processing', 'error_message' => null]);
        Log::info("Début de l'analyse pour TextAnalysis ID: {$this->text_analysis_id}");

        try {
            // $flaskApiUrl = env('AI_DETECTION_API_URL', 'http://127.0.0.1:5000/check-plagiarism');

            // Appel à l'API Flask
            $response = Http::timeout(900)->post("http://127.0.0.1:5000/check-plagiarism", ['text' => $this->content]);

            Log::info($response);

            if ($response->successful()) {
                $result = $response->json();
                $resultData = $result['similarities'] ?? [];

                $similaritiesData = $resultData['similarities']['similarities'] ?? [];
                $excerptedText = $resultData['similarities']['excerpted_text'] ?? [];
                $highlightedText = $resultData['highlighted_text'] ?? null;
                $isAiGenerated = isset($result['ai_generated_probability']) && $result['ai_generated_probability'] > 0.6;
                $plagiarismPercentage = $resultData['plagiarism_percentage'] ?? 0;

                $analysis->update([
                    'plagiarism_percentage' => $plagiarismPercentage,
                    'excerpted_text' => $excerptedText,
                    'similarities' => json_encode($similaritiesData),
                    'highlighted_text' => $highlightedText,
                    'is_ai_generated' => $isAiGenerated,
                    'status' => 'completed',
                    'error_message' => null,
                ]);

                // Mettre à jour le document parent si nécessaire
                if ($analysis->document) {
                    $analysis->document->update(['has_been_analyzed' => true]);
                }
                Log::info("Analyse de plagiat terminée avec succès pour TextAnalysis ID: {$this->text_analysis_id}");

                try {
                    Log::info("Dispatching PlagiarismAnalysisCompleted event...");
                    
                    PlagiarismAnalysisCompleted::dispatch($this->text_analysis_id, 'completed');

                    Log::info("PlagiarismAnalysisCompleted BROADCAST ATTEMPTED SUCCESSFULLY. ✅");
                } catch (\Throwable $th) {
                    Log::error("BROADCASTING FAILED FOR PlagiarismAnalysisCompleted (Flask API Error)! ", [
                        'analysis_id' => $this->text_analysis_id,
                        'error_message' => $th->getMessage(),
                        'exception_trace' => $th->getTraceAsString(),
                    ]);
                    throw $th;
                }
            } else {
                // Gérer les erreurs de l'API Flask (4xx, 5xx)
                $errorMessage = "API Flask a échoué. Statut: {$response->status()}, Réponse: {$response->body()}";
                $analysis->update([
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                ]);
                PlagiarismAnalysisCompleted::dispatch($this->text_analysis_id, 'failed');

                Log::error("Erreur API Flask pour TextAnalysis ID: {$this->text_analysis_id}. {$errorMessage}");
            }
        } catch (\Exception $e) {
            $analysis->update([
                'status' => 'failed',
                'error_message' => 'Erreur inattendue: ' . $e->getMessage(),
            ]);
            Log::error("Exception pour TextAnalysis ID: {$this->text_analysis_id}. Erreur: " . $e->getMessage());
            
            try {
                PlagiarismAnalysisCompleted::dispatch($this->text_analysis_id, 'failed');
                Log::info("FAILURE EVENT (from general exception) BROADCAST ATTEMPTED SUCCESSFULLY. ✅");
                throw $e;
            } catch (\Throwable $th) {
                Log::error("BROADCASTING FAILED FOR PlagiarismAnalysisCompleted (General Exception)! ❌", [
                    'analysis_id' => $this->text_analysis_id,
                    'error_message' => $e->getMessage(),
                    'exception_trace' => $e->getTraceAsString(),
                ]);
                throw $th;
            }
        }
    }

    /**
     * Définit le nombre de secondes pendant lesquelles le job peut s'exécuter avant d'expirer.
     * @var int
     */
    public $timeout = 1000;

    /**
     * Le nombre de fois que le job peut être retenté.
     * @var int
     */
    public $tries = 3;
}
