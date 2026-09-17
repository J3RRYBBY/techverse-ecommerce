<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
require __DIR__ . '/admin.php';

// Route::get('/', function () {
//     return view('user.home');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/login-redirect', function () {
    session()->put('url.intended', url()->previous());

    return redirect()->route('login');
})->name('user#loginRedirect');

// Social Login
Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])->name('social#redirect');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])->name('social#callback');
