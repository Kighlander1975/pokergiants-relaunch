<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$url = 'http://127.0.0.1:8000/verify-email/3/21adc4da6d467ae073460fdcd1462a3abeebc9d2?expires=1767078433&signature=eca1b85de3d6ddd8db52b1ba1343bc527324baa387c80370f93b3b7a40719a5b';
$request = Illuminate\Http\Request::create($url);
$valid = app('url')->hasValidSignature($request);
echo $valid ? 'Valid' : 'Invalid';
