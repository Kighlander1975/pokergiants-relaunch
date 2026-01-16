<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_online_at' => 'datetime',
        ];
    }

    public function getLastOnlineAtAttribute(?Carbon $value): ?Carbon
    {
        return $value ?? $this->updated_at;
    }

    public function getLastOnlineLabelAttribute(): string
    {
        $hasTrackedTimestamp = ! is_null($this->attributes['last_online_at'] ?? null);
        $lastOnlineAt = $this->lastOnlineAt;

        if (! $lastOnlineAt) {
            return 'Unbekannt';
        }

        return $this->formatLastOnlineLabel($lastOnlineAt, $hasTrackedTimestamp);
    }

    private function formatLastOnlineLabel(Carbon $timestamp, bool $hasTrackedTimestamp): string
    {
        $now = now();

        if ($timestamp->greaterThan($now)) {
            return 'Jetzt';
        }

        $diffMinutes = (int) abs($now->diffInMinutes($timestamp, false));
        if ($hasTrackedTimestamp && $diffMinutes <= 5) {
            return 'Jetzt';
        }

        if ($diffMinutes < 60) {
            return 'vor ' . $diffMinutes . ' Minuten';
        }

        $diffHours = (int) abs($now->diffInHours($timestamp, false));
        if ($diffHours < 24) {
            $unit = $diffHours === 1 ? 'Stunde' : 'Stunden';
            return 'vor ' . $diffHours . ' ' . $unit;
        }

        $diffDays = (int) abs($now->diffInDays($timestamp, false));
        if ($diffDays < 7) {
            return $diffDays === 1 ? 'vor einem Tag' : 'vor ' . $diffDays . ' Tagen';
        }

        if ($diffDays < 14) {
            return 'vor einer Woche';
        }

        if ($diffDays < 21) {
            return 'vor zwei Wochen';
        }

        if ($diffDays < 28) {
            return 'vor drei Wochen';
        }

        $diffMonths = (int) abs($now->diffInMonths($timestamp, false));
        if ($diffMonths <= 1) {
            return 'vor einem Monat';
        }

        if ($diffMonths < 12) {
            return 'vor ' . $diffMonths . ' Monaten';
        }

        $diffYears = (int) abs($now->diffInYears($timestamp, false));
        if ($diffYears <= 1) {
            return 'vor einem Jahr';
        }

        return 'vor ' . $diffYears . ' Jahren';
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Hilfsmethode: Avatar-URL über UserDetail abrufen
     *
     * @param string $conversion Die gewünschte Media Conversion (z.B. 'thumb', 'small', 'large')
     * @return string Die URL des Avatars oder Fallback-Bild
     */
    public function getAvatarUrl(string $conversion = ''): string
    {
        // Zuerst Media Library prüfen
        if ($this->userDetail && $this->userDetail->hasMedia('avatar')) {
            return $this->userDetail->getFirstMediaUrl('avatar', $conversion);
        }

        // Fallback auf avatar_image_filename Feld
        if ($this->userDetail && $this->userDetail->avatar_image_filename) {
            return asset('storage/avatars/' . $this->userDetail->avatar_image_filename);
        }

        return asset('images/default-avatar.png'); // Fallback
    }

    /**
     * Prüfen ob User einen Avatar hat
     *
     * @return bool True wenn der User einen Avatar hat
     */
    public function hasAvatar(): bool
    {
        // Media Library prüfen
        if ($this->userDetail && $this->userDetail->hasMedia('avatar')) {
            return true;
        }

        // avatar_image_filename Feld prüfen
        return $this->userDetail && !empty($this->userDetail->avatar_image_filename);
    }
}
