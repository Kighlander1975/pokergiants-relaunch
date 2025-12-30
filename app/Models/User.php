<?php

namespace App\Models;

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
        ];
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
        if ($this->userDetail && $this->userDetail->hasMedia('avatar')) {
            return $this->userDetail->getFirstMediaUrl('avatar', $conversion);
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
        return $this->userDetail && $this->userDetail->hasMedia('avatar');
    }
}
