<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('App');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});
Route::prefix('auth')
    // ->middleware(['email.transform'])
    ->group(function () {
        Route::get('login', [LoginController::class, 'index'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');

        Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('forgot-password.index');
        Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('forgot-password');

        Route::get('password/reset/{token}', [ResetPasswordController::class, 'index'])->name('password.reset');
        Route::post('password/reset', [ResetPasswordController::class, 'store'])->name('password.update');

        Route::get('register', [RegisterController::class, 'index'])->name('register.index');
        Route::post('register', [RegisterController::class, 'register'])->name('register.store');

        Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
        Route::get('email/verification-notification', [VerificationController::class, 'sendVerificationEmail'])->name('verification.send');
        Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->name('verification.verify');
    });

//No Tenant Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('auth/logout', LogoutController::class, [
        'names' => [
            'index' => 'logout',
        ],
    ])->only(['index']);
});


// <?php

// use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return inertia('App');
// });
// Route::get('/login', function () {
//     return inertia('Auth/Login');
// });
// Route::get('/dashboard', function () {
//     return inertia('Dashboard');
// });
// Route::get('/navbar', function () {
//     return inertia('NavBar');
// });
// Route::get('/photos', function () {
//     return inertia('Photos');
// });
// Route::get('/setting', function () {
//     return inertia('Setting');
// });
// Route::get('/calender', function () {
//     return inertia('Calender');
// });
// Route::get('/teams', function () {
//     return inertia('Teams');
// });
// Route::get('/full-page', function () {
//     return inertia('FullPageLayout');
// }); -->
