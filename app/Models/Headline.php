<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Headline extends Model
{
    protected $fillable = [
        'section_id',
        'headline_text',
        'subline_text',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
