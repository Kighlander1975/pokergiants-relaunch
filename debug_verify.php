<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::find(3);
echo 'User: ' . ($user ? $user->email : 'null') . PHP_EOL;
echo 'Verified: ' . ($user && $user->hasVerifiedEmail() ? 'yes' : 'no') . PHP_EOL;
