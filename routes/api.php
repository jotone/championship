<?php

use App\Http\Controllers\Api\{RolesController, UsersController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('/roles', RolesController::class)->except(['create', 'edit', 'show']);
Route::resource('/users', UsersController::class)->except(['create', 'edit']);
