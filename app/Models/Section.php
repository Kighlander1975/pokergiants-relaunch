<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'section_name',
    ];

    public function headline()
    {
        return $this->hasOne(Headline::class, 'section_id');
    }

    public function widgets()
    {
        return $this->hasMany(Widget::class, 'section_id');
    }
}
