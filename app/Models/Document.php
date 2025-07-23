<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'name',
        'file_url',
        'content',
        'has_been_analyzed',
        'user_id',
    ];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function text_analyses(): HasMany
    {
        return $this->hasMany(TextAnalysis::class);
    }
}
