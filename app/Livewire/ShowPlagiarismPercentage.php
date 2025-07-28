<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TextAnalysis;

class ShowPlagiarismPercentage extends Component
{
    public $textAnalysisId;
    public $percentage = 0;
    public $aiGeneratedProbability = 0;

    public function mount($textAnalysisId)
    {
        $this->textAnalysisId = $textAnalysisId;
        $this->loadPercentage();
    }

    public function loadPercentage()
    {
        $textAnalysis = TextAnalysis::find($this->textAnalysisId);
        if ($textAnalysis) {
            $this->percentage = $textAnalysis->plagiarism_percentage;
            $this->aiGeneratedProbability = $textAnalysis->is_ai_generated ? (int) $textAnalysis->ai_generated_probability * 100 : 0;
        }
    }

    public function render()
    {
        return view('livewire.show-plagiarism-percentage');
    }
}
