<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'author_external',
        'tags',
        'category',
        'source',
        'comments_allowed',
        'published',
        'auto_publish_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'source' => 'array',
        'comments_allowed' => 'boolean',
        'published' => 'boolean',
        'auto_publish_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (News $news) {
            if ($news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('published', true)
            ->where(function ($query) {
                $query->whereNull('auto_publish_at')->orWhere('auto_publish_at', '<=', now());
            });
    }
}
