<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    /** @use HasFactory<\Database\Factories\UniversityFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'address', 'phone', 'admin_id', 'subscription_id'];
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
