<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Kighlander (admin)
        $user = User::firstOrCreate(
            ['email' => 'kai.akkermann@kighlander.de'],
            [
                'nickname' => 'Kighlander',
                'email_verified_at' => now(),
                'password' => '$2y$12$jspc8YJIXjihjN3J5ClLOuk3ejtbX61eW5Nv980Z9pWTUK1Hpbpey',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'last_online_at' => now(),
            ]
        );

        UserDetail::firstOrCreate(
            ['user_id' => $user->id],
            [
                'role' => 'admin',
                'firstname' => 'Kai',
                'lastname' => 'Akkermann',
                'street_number' => 'Rennerskamp 16 B',
                'zip' => '32289',
                'city' => 'Rödinghausen',
                'country_flag' => 'de_DE',
                'avatar_display_mode' => 'nickname',
                'bio' => 'Coming soon...',
                'dob' => '1975-12-28',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create MrKighPoker (player)
        $user = User::firstOrCreate(
            ['email' => 'kighlander@live.de'],
            [
                'nickname' => 'MrKighPoker',
                'email_verified_at' => now(),
                'password' => '$2y$12$2HIUY5w/lzCImk4lbW1zperpXcNlMNN95g/Qt18Npdb8ASKbS6sNy',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'last_online_at' => now(),
            ]
        );

        UserDetail::firstOrCreate(
            ['user_id' => $user->id],
            [
                'role' => 'player',
                'country_flag' => 'de_DE',
                'avatar_display_mode' => 'nickname',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
