<?php

namespace Database\Seeders;

use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AvatarMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find Kighlander user
        $userDetail = UserDetail::whereHas('user', function($query) {
            $query->where('email', 'kai.akkermann@kighlander.de');
        })->first();

        if ($userDetail) {
            // Check if media already exists
            if (!$userDetail->hasMedia('avatar')) {
                // Check if the avatar file exists in storage
                $avatarPath = storage_path('app/public/2/avatar_3_1767111024.jpg');

                if (file_exists($avatarPath)) {
                    // Add the existing file to media library
                    $mediaFile = $userDetail->addMedia($avatarPath)
                        ->usingName('Avatar')
                        ->usingFileName('avatar_3_1767111024.jpg')
                        ->toMediaCollection('avatar');

                    // Mark conversions as already generated
                    $mediaFile->generated_conversions = [
                        'thumb' => true,
                        'small' => true,
                        'large' => true,
                    ];
                    $mediaFile->save();

                    $this->command->info('Avatar media entry created for Kighlander (ID: ' . $mediaFile->id . ')');
                } else {
                    $this->command->error('Avatar file not found: ' . $avatarPath);
                    $this->command->info('Please ensure the avatar file exists in storage/app/public/2/');
                }
            } else {
                $this->command->info('Avatar media already exists for Kighlander');
            }
        } else {
            $this->command->error('Kighlander user not found');
        }
    }
}