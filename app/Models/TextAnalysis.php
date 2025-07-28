<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextAnalysis extends Model
{
    /** @use HasFactory<\Database\Factories\TextAnalysisFactory> */
    use HasFactory;
    protected $fillable = [
        'document_id',
        'user_id',
        'similarities',
        'highlighted_text',
        'excerpted_text',
        'plagiarism_percentage',
        'ai_generated_probability',
        'is_ai_generated',
        'ai_generated_label',
        'status',
        'error_message',
    ];

    protected $casts = [
        // 'highlighted_text' => 'array',
        'plagiarism_percentage' => 'float',
        'is_ai_generated' => 'boolean',
        'status' => 'string',
        'excerpted_text' => 'array',
        'similarities' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
