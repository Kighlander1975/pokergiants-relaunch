<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserDetail extends Model implements HasMedia
{
    use InteractsWithMedia;

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
        'avatar_display_mode',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Media Library: Avatar Collection definieren
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    /**
     * Media Library: Automatische Avatar-Conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued(); // Sofort verarbeiten

        $this->addMediaConversion('small')
            ->width(50)
            ->height(50)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();
    }

    /**
     * Hilfsmethode: Avatar-URL abrufen
     */
    public function getAvatarUrl(string $conversion = ''): string
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMediaUrl('avatar', $conversion);
        }

        return asset('images/default-avatar.png'); // Fallback
    }

    /**
     * Hilfsmethode: Avatar-Display-Modus abrufen
     */
    public function getAvatarDisplayMode(): string
    {
        return $this->avatar_display_mode ?? 'nickname';
    }
}
