<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    public const CATEGORY_INTERNAL = 'interne_pokernews';
    public const CATEGORY_EXTERNAL = 'externe_pokernews';

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
        'content',
    ];

    protected $casts = [
        'tags' => 'array',
        'source' => 'array',
        'comments_allowed' => 'boolean',
        'published' => 'boolean',
        'auto_publish_at' => 'datetime',
    ];

    public static function categories(): array
    {
        return [
            self::CATEGORY_INTERNAL => 'Interne Pokernews',
            self::CATEGORY_EXTERNAL => 'Externe Pokernews',
        ];
    }

    public static function generateSlug(string $title, int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
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
