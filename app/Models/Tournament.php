<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Location;

class Tournament extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'location_id',
        'name',
        'starts_at',
        'registration_info',
        'prices',
        'description',
        'is_ranglistenturnier',
        'is_published',
        'is_registration_open',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'prices' => 'array',
        'is_ranglistenturnier' => 'boolean',
        'is_published' => 'boolean',
        'is_registration_open' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopePlayed($query)
    {
        return $query->where('starts_at', '<', now());
    }
}
