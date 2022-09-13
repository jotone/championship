<?php

use App\Http\Controllers\Admin\{
    DashboardController, CountryController, RolesController, TeamController, UsersController
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

Route::group(['as' => 'countries.', 'prefix' => '/countries'], function () {
    Route::resource('/teams', TeamController::class)->only(['index', 'create', 'edit']);
});

Route::resource('/countries', CountryController::class)->only(['index', 'create', 'edit']);

Route::group(['as' => 'users.', 'prefix' => '/users'], function () {
    Route::resource('/roles', RolesController::class)->only(['index', 'create', 'edit']);
});

Route::resource('/users', UsersController::class)->only(['index', 'create', 'edit']);