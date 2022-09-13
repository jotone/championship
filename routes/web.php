<?php

use App\Http\Controllers\Main\{AuthController, HomeController, PasswordResetController, RegistrationController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::any('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::group(['as' => 'registration.'], function () {
    Route::get('/registration', [RegistrationController::class, 'index'])->name('index');
    Route::post('/registration', [RegistrationController::class, 'store'])->name('store');
    Route::get('/email-confirmation/{token}', [RegistrationController::class, 'confirmation'])->name('confirmation');
});

Route::group(['as' => 'password-reset.'], function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'index'])->name('index');// Request form
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('store');//Send email
    Route::get('/password-reset/{token}', [PasswordResetController::class, 'form'])->name('form');
    Route::post('/password-reset/{token}', [PasswordResetController::class, 'update'])->name('update');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');
