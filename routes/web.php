<?php

use App\Http\Controllers\Main\{
    AuthController, HomeController, PasswordResetController, RegistrationController, UserController, UserFormController
};
use App\Models\Settings;
use Illuminate\Support\Facades\{Route, View};

$settings = Settings::whereIn('key', ['registration_enable'])->get()->keyBy('key');
View::share('settings', $settings);

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

if ($settings->count() && $settings['registration_enable']->converted_value) {
    Route::group(['as' => 'registration.'], function () {
        Route::get('/registration', [RegistrationController::class, 'index'])->name('index');
        Route::post('/registration', [RegistrationController::class, 'store'])->name('store');
        Route::get('/email-confirmation/{token}', [RegistrationController::class, 'confirmation'])->name('confirmation');
    });
}

Route::group(['as' => 'password-reset.'], function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'index'])->name('index');// Request form
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('store');//Send email
    Route::get('/password-reset/{token}', [PasswordResetController::class, 'form'])->name('form');
    Route::post('/password-reset/{token}', [PasswordResetController::class, 'update'])->name('update');
});

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::group(['as' => 'user.', 'prefix' => '/user'], function () {
    Route::get('/form', [UserFormController::class, 'show'])->name('form.show');
    Route::post('/form/{competition}', [UserFormController::class, 'store'])->name('form.store');

    Route::get('/profile/{id?}', [UserController::class, 'show'])->name('profile.show');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::get('/results/{id}', [UserController::class, 'results'])->name('results');
});
