<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'kai.akkermann@kighlander.de')->first();

if ($user) {
    echo 'User found: ' . $user->nickname . PHP_EOL;
    echo 'Has Avatar: ' . ($user->hasAvatar() ? 'YES' : 'NO') . PHP_EOL;
    echo 'Avatar URL: ' . $user->getAvatarUrl('small') . PHP_EOL;

    if ($user->userDetail) {
        echo 'UserDetail exists' . PHP_EOL;
        echo 'avatar_image_filename: ' . ($user->userDetail->avatar_image_filename ?? 'null') . PHP_EOL;
        echo 'Has Media: ' . ($user->userDetail->hasMedia('avatar') ? 'YES' : 'NO') . PHP_EOL;
    } else {
        echo 'No UserDetail' . PHP_EOL;
    }
} else {
    echo 'User not found' . PHP_EOL;
}