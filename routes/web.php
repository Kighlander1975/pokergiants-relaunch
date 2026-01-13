<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\ProfileCompletionController;
use App\Http\Controllers\CredentialsController;
use App\Http\Controllers\TournamentPublicController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\NewsCommentController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\TournamentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Support\AvatarTempCleanup;
use App\Http\Controllers\NewsPublicController;

Route::get('/', function () {
    return view('layouts.frontend.pages.home');
})->name('home');

Route::get('/about', function () {
    return view('layouts.frontend.pages.about');
})->name('about')->withoutMiddleware(['check.user.details']);

Route::get('/datenschutz', function () {
    return view('layouts.frontend.pages.datenschutz');
})->name('datenschutz')->withoutMiddleware(['check.user.details']);

Route::get('/registration-success', function () {
    return view('layouts.frontend.pages.registration-success');
})->name('registration.success');

// Avatar Routes (nur für eingeloggte User)
Route::middleware('auth')->group(function () {
    Route::get('/avatar/edit', [App\Http\Controllers\AvatarController::class, 'edit'])->name('avatar.edit');
    Route::post('/avatar/upload', [App\Http\Controllers\AvatarController::class, 'upload'])->name('avatar.upload');
    Route::post('/avatar/crop', [App\Http\Controllers\AvatarController::class, 'crop'])->name('avatar.crop');
    Route::patch('/avatar/display-mode', [App\Http\Controllers\AvatarController::class, 'updateDisplayMode'])->name('avatar.updateDisplayMode');
    Route::delete('/avatar', [App\Http\Controllers\AvatarController::class, 'destroy'])->name('avatar.destroy');
});

Route::fallback(function () {
    return view('errors.404');
})->name('404')->withoutMiddleware(['check.user.details']);

Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'check.role:admin,floorman', 'track.user.activity'])->name('dashboard');

Route::get('/turniere', [TournamentPublicController::class, 'index'])->name('tournaments.index');
Route::get('/news', [NewsPublicController::class, 'index'])->name('news.index');
Route::get('/news/{news}/{slug?}', [NewsPublicController::class, 'show'])->name('news.show');

// Admin routes
Route::middleware(['auth', 'verified', 'check.role:admin,floorman', 'track.user.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/password', [App\Http\Controllers\AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::patch('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::patch('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::patch('/tournaments/{tournament}/publish', [TournamentController::class, 'publish'])->name('tournaments.publish');
    Route::patch('/tournaments/{tournament}/unpublish', [TournamentController::class, 'unpublish'])->name('tournaments.unpublish');
    Route::patch('/tournaments/{tournament}/open-registration', [TournamentController::class, 'openRegistration'])->name('tournaments.open-registration');
    Route::patch('/tournaments/{tournament}/close-registration', [TournamentController::class, 'closeRegistration'])->name('tournaments.close-registration');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');

    Route::resource('news', NewsController::class)->except(['show']);
    Route::patch('/news/{news}/publish', [NewsController::class, 'publish'])->name('news.publish');
    Route::patch('/news/{news}/unpublish', [NewsController::class, 'unpublish'])->name('news.unpublish');

    Route::get('/news/{news}/comments', [NewsCommentController::class, 'index'])->name('news.comments.index');
    Route::patch('/news/{news}/comments/{comment}/approve', [NewsCommentController::class, 'approve'])->name('news.comments.approve');
    Route::patch('/news/{news}/comments/{comment}/reject', [NewsCommentController::class, 'reject'])->name('news.comments.reject');
});

Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::get('/email/verification-notification', [EmailVerificationNotificationController::class, 'show'])
    ->name('verification.send');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Credentials routes
    Route::get('/credentials', [CredentialsController::class, 'edit'])->name('credentials.edit');
    Route::patch('/credentials/email', [CredentialsController::class, 'updateEmail'])->name('credentials.update.email');
    Route::patch('/credentials/password', [CredentialsController::class, 'updatePassword'])->name('credentials.update.password');
    Route::get('/credentials/verify-email/{token}', [CredentialsController::class, 'verifyEmailChange'])->name('credentials.verify.email');
});

Route::get('/dev/cleanup-temp-avatars', function () {
    $result = AvatarTempCleanup::cleanup(0); // 0 Tage = ALLE temp_* löschen

    dd($result);
});

require __DIR__ . '/auth.php';
