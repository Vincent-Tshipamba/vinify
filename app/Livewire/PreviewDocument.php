<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TextAnalysis;

class PreviewDocument extends Component
{

    public $textAnalysisId;
    public $fileUrl;
    public $documentName;
    public $documentContent;
    public $fileExtension;

    public function mount($textAnalysisId)
    {
        $this->textAnalysisId = $textAnalysisId;
        $this->loadDocumentContent();
    }

    public function loadDocumentContent()
    {
        $textAnalysis = TextAnalysis::find($this->textAnalysisId);
        if ($textAnalysis) {
            $this->documentContent = $textAnalysis->document->content ?? '';
            $this->fileUrl = $textAnalysis->document->file_url ?? '';
            $this->documentName = $textAnalysis->document->name ?? '';
        } else {
            $this->documentContent = 'Document not found.';
        }
    }

    public function render()
    {
        return view('livewire.preview-document');
    }
}
