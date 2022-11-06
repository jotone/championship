<?php

use App\Http\Controllers\Admin\{
    CompetitionController,
    CountryController,
    CustomPagesController,
    DashboardController,
    ForumController,
    LanguageController,
    RolesController,
    SettingsController,
    TeamController,
    UsersController
};
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

Route::get('/', [DashboardController::class, 'index'])->name('index');

// Competitions
Route::resource('/competitions', CompetitionController::class)->only(['index', 'create', 'edit']);

// Countries and teams
Route::group(['as' => 'countries.', 'prefix' => '/countries'], function () {
    Route::resource('/teams', TeamController::class)->only(['index', 'create', 'edit']);
});

Route::resource('/countries', CountryController::class)->only(['index', 'create', 'edit']);

// Users and roles
Route::group(['as' => 'users.', 'prefix' => '/users'], function () {
    // Role list
    Route::resource('/roles', RolesController::class)->only(['index', 'create', 'edit']);
});
// User list
Route::resource('/users', UsersController::class)->only(['index', 'create', 'edit']);
// Forum topic list
Route::resource('/forum', ForumController::class)->only(['index', 'show', 'create', 'edit']);
// Custom page list
Route::resource('/pages', CustomPagesController::class)->only(['index', 'create', 'edit']);
// Settings
Route::group(['as' => 'settings.', 'prefix' => '/settings'], function () {
    // Main settings
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    // Color scheme settings
    Route::get('/color-scheme', [SettingsController::class, 'colorScheme'])->name('color-scheme.index');
    // Update settings
    Route::patch('/', [SettingsController::class, 'update'])->name('update');
    // Language settings page
    Route::resource('/languages', LanguageController::class)->only(['index', 'store', 'update', 'destroy']);
//    // Language settings page
//    Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
//    // Install new language
//    Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
//    // Update language settings
//    Route::put('/languages', [LanguageController::class, 'update'])->name('languages.update');
//    // Update language translation
//    Route::patch('languages', [LanguageController::class, 'upgrade'])->name('languages.upgrade');
//    // Remove language

});
