<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Widget extends Model
{
    protected $fillable = [
        'section_id',
        'internal_name',
        'widget_type',
        'width_percentage',
        'center_on_small',
        'content_html',
        'content_plain',
        'content_type',
        'sort_order',
    ];

    protected $casts = [
        'center_on_small' => 'boolean',
        'width_percentage' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the section that owns the widget.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the CSS classes for this widget.
     */
    public function getCssClassesAttribute(): string
    {
        $classes = ['glass-card'];

        if ($this->widget_type === 'one-card') {
            $classes[] = 'one-card';
            if ($this->width_percentage) {
                $classes[] = "one-card-{$this->width_percentage}";
            }
        } else {
            if ($this->width_percentage) {
                $classes[] = "card-{$this->width_percentage}";
            }
        }

        if ($this->center_on_small) {
            $classes[] = 'center-on-small';
        }

        return implode(' ', $classes);
    }

    /**
     * Scope to order widgets by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
