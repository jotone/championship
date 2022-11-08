<?php

use App\Http\Controllers\Api\{
    CompetitionController,
    CompetitionGroupController,
    CompetitionGroupGameController,
    CompetitionGroupTeamController,
    CountryController,
    CustomPagesController,
    ForumController,
    ForumMessageController,
    LanguageController,
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

// Competitions
Route::resource('/competitions', CompetitionController::class)->except(['create', 'edit', 'show']);

// Competition groups
Route::patch('/competition-groups', [CompetitionGroupController::class, 'upgrade'])->name('competition-groups.upgrade');
Route::resource('/competition-groups', CompetitionGroupController::class)->except(['create', 'edit', 'show']);

// Competition games
Route::group(['prefix' => '/competition-group-games', 'as' => 'competition-group-games.'], function () {
    Route::post('/create', [CompetitionGroupGameController::class, 'create'])->name('create');
    Route::post('/', [CompetitionGroupGameController::class, 'store'])->name('store');
    Route::match(['patch', 'put'], '/{competition_group_game}', [CompetitionGroupGameController::class, 'update'])->name('update');
    Route::delete('/{competition_group_game}', [CompetitionGroupGameController::class, 'destroy'])->name('destroy');
    Route::delete('/{competition_group_game}/{team_id}', [CompetitionGroupGameController::class, 'delete'])->name('delete');
});

// Competition teams
Route::resource('/competition-group-teams', CompetitionGroupTeamController::class)->only(['store', 'destroy']);

// Countries
Route::resource('/countries', CountryController::class)->except(['create', 'edit', 'show']);

// Teams
Route::resource('/teams', TeamController::class)->except(['create', 'edit', 'show']);

// Roles
Route::resource('/roles', RolesController::class)->except(['create', 'edit', 'show']);

// Users
Route::resource('/users', UsersController::class)->except(['create', 'edit']);

// Forum topics
Route::patch('/forum/upgrade', [ForumController::class, 'upgrade'])->name('forum.upgrade');
Route::resource('/forum', ForumController::class)->except(['create', 'edit', 'show']);

// Forum messages
Route::resource('/forum-messages', ForumMessageController::class)->only(['update', 'destroy']);

// Custom pages
Route::resource('/pages', CustomPagesController::class)->except(['create', 'edit', 'show']);

// Languages
Route::patch('/languages/upgrade', [LanguageController::class, 'upgrade'])->name('languages.upgrade');
Route::resource('/languages', LanguageController::class)->except(['index', 'create', 'edit']);
