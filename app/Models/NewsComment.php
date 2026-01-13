<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'parent_id',
        'user_id',
        'author_display',
        'content',
        'level',
        'is_approved',
        'pending_content',
        'pending_author_display',
        'pending_user_id',
        'pending_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pendingUser()
    {
        return $this->belongsTo(User::class, 'pending_user_id');
    }

    public function getDisplayContentAttribute(): string
    {
        return $this->pending_content ?? $this->content;
    }

    public function getDisplayAuthorAttribute(): ?string
    {
        return $this->pending_author_display ?? $this->author_display;
    }

    public function markPending(string $content, ?string $authorDisplay, ?int $userId): void
    {
        $this->pending_content = $content;
        $this->pending_author_display = $authorDisplay;
        $this->pending_user_id = $userId;
        $this->pending_at = now();
        $this->is_approved = false;
    }

    public function approvePending(): void
    {
        if (!$this->pending_content) {
            return;
        }

        $this->content = $this->pending_content;
        $this->author_display = $this->pending_author_display ?? $this->author_display;
        $this->pending_content = null;
        $this->pending_author_display = null;
        $this->pending_user_id = null;
        $this->pending_at = null;
        $this->is_approved = true;
    }
}
