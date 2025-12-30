<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'firstname',
        'lastname',
        'street_number',
        'zip',
        'city',
        'country',
        'country_flag',
        'avatar_image_filename',
        'bio',
        'dob',
        'giants_card',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
