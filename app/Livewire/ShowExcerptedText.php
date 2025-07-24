<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TextAnalysis;

class ShowExcerptedText extends Component
{
    public $textAnalysisId;
    public $excerptedText = [];

    public function mount($textAnalysisId)
    {
        
        $this->textAnalysisId = $textAnalysisId;
        $this->loadExcerptedText();
    }

    public function loadExcerptedText()
    {
        $textAnalysis = TextAnalysis::find($this->textAnalysisId);
        if (!$textAnalysis) {
            session()->flash('error', 'Analyse de texte non trouvée.');
            return;
        }

        $this->excerptedText = $textAnalysis->excerpted_text ?? [];
    }

    public function render()
    {
        return view('livewire.show-excerpted-text');
    }
}
