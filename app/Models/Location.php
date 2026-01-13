<?php

namespace App\Models;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'street',
        'postal_city',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
