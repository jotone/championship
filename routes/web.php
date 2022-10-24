<?php

use App\Http\Controllers\Main\{
    AuthController,
    ForumController,
    HomeController,
    PasswordResetController,
    RegistrationController,
    UserController,
    UserFormController
};
use App\Models\{CustomPage, Settings};
use Illuminate\Support\Facades\{Route, Schema};

$registration_enable = Schema::hasTable('settings') ? Settings::firstWhere(['key' => 'registration_enable']) : false;

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

// Sign In request
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
// Sign Out request
Route::any('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// If registration was allowed
if (!empty($registration_enable) && $registration_enable->converted_value) {
    // Registration routes
    Route::group(['as' => 'registration.'], function () {
        // View registration page
        Route::get('/registration', [RegistrationController::class, 'index'])->name('index');
        // Send registration request
        Route::post('/registration', [RegistrationController::class, 'store'])->name('store');
        // Email confirmation route
        Route::get('/email-confirmation/{token}', [RegistrationController::class,
                                                   'confirmation'])->name('confirmation');
    });
}

// Authorization help pages
Route::group(['as' => 'password-reset.'], function () {
    // View a Forgot Password page
    Route::get('/forgot-password', [PasswordResetController::class, 'index'])->name('index');// Request form
    // Send the forgot password request
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('store');//Send email
    // View a password reset form page
    Route::get('/password-reset/{token}', [PasswordResetController::class, 'form'])->name('form');
    // The Password reset handler
    Route::post('/password-reset/{token}', [PasswordResetController::class, 'update'])->name('update');
});

// User pages
Route::group(['as' => 'user.', 'prefix' => '/user'], function () {
    // View user form
    Route::get('/form', [UserFormController::class, 'show'])->name('form.show');
    // Save user form
    Route::post('/form/{competition}', [UserFormController::class, 'store'])->name('form.store')->middleware('auth');
    // View user profile
    Route::get('/profile/{id?}', [UserController::class, 'show'])->name('profile.show');
    // Save user profile
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update')->middleware('auth');
    // View user form compare
    Route::get('/results/{id}', [UserController::class, 'results'])->name('results');
});

// Games Schedule page
Route::get('/schedule', [HomeController::class, 'schedule'])->name('schedule.index');
// Games by groups
Route::get('/groups', [HomeController::class, 'groups'])->name('groups.index');
// Forum
Route::resource('/forum', ForumController::class)->only(['index', 'show']);

// Custom pages
if (Schema::hasTable('custom_pages')) {
    foreach (CustomPage::where('editable', 1)->where('enabled', 1)->get() as $page) {
        Route::get($page->url, [HomeController::class, 'index'])->name($page->slug . '.index');
    }
}
