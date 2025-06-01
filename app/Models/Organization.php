<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;


    protected $fillable = ['name', 'slug', 'owner_id'];

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
