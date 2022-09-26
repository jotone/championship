<?php

use App\Http\Controllers\Api\{
    CompetitionController,
    CompetitionGroupController,
    CompetitionGroupGameController,
    CompetitionGroupTeamController,
    CountryController,
    RolesController,
    TeamController,
    UsersController
};
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

Route::resource('/competition-group-games', CompetitionGroupGameController::class)->only(['update']);
Route::resource('/competition-group-teams', CompetitionGroupTeamController::class)->only(['store', 'destroy']);
Route::patch('/competition-groups', [CompetitionGroupController::class, 'upgrade'])->name('competition-groups.upgrade');
Route::resource('/competition-groups', CompetitionGroupController::class)->only(['index', 'update', 'destroy']);
Route::resource('/competitions', CompetitionController::class)->except(['create', 'edit', 'show']);
Route::resource('/countries', CountryController::class)->except(['create', 'edit', 'show']);
Route::resource('/roles', RolesController::class)->except(['create', 'edit', 'show']);
Route::resource('/teams', TeamController::class)->except(['create', 'edit', 'show']);
Route::resource('/users', UsersController::class)->except(['create', 'edit']);
