<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextAnalysis extends Model
{
    /** @use HasFactory<\Database\Factories\TextAnalysisFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'similarities',
        'is_ai_generated',
        'status',
        'error_message',
    ];

    protected $casts = [
        'similarities' => 'array',
        'ai_generated_probability' => 'float',
    ];    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
