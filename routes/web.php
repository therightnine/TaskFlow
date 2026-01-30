<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EquipeController;


/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboards (selon rôle)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/chef', [DashboardController::class, 'chef'])
    ->name('dashboard.chef');
Route::get('/dashboard/contributeur', [DashboardController::class, 'contributeur'])
    ->name('dashboard.contributeur');
Route::get('/dashboard/superviseur', [DashboardController::class, 'superviseur'])
    ->name('dashboard.superviseur');

/*
|--------------------------------------------------------------------------
| Projets (CRUD) → ProjetController
|--------------------------------------------------------------------------
*/
Route::get('/projects', [ProjetController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/create', [ProjetController::class, 'create'])
    ->name('projects.create');

Route::post('/projects', [ProjetController::class, 'store'])
    ->name('projects.store');

Route::get('/projects/{project}/edit', [ProjetController::class, 'edit'])
    ->name('projects.edit');

Route::put('/projects/{project}', [ProjetController::class, 'update'])
    ->name('projects.update');

Route::delete('/projects/{project}', [ProjetController::class, 'destroy'])
    ->name('projects.destroy');

Route::post('/projects/{project}/archive', [ProjetController::class, 'archive'])
    ->name('projects.archive');

Route::post('/projects/{project}/favorite', [ProjetController::class, 'toggleFavorite'])
    ->name('projects.favorite');

Route::post('/projects/{project}/add-supervisor', [ProjetController::class, 'addSupervisor'])
    ->name('projects.updateSupervisor');

Route::post('/projects/{project}/add-contributor', [ProjetController::class, 'addContributor'])
    ->name('projects.addContributor');
Route::post('/projects/{project}/contributor-toggle', [ProjetController::class, 'toggleContributor']);
Route::post('/projects/{project}/supervisor-toggle', [ProjetController::class, 'toggleSupervisor']);




/*
|--------------------------------------------------------------------------
| Pages Createur (DashboardController)
|--------------------------------------------------------------------------
*/

Route::get('/tasks', [DashboardController::class, 'tasks'])
    ->name('chef.tasks');

Route::get('/reports', [DashboardController::class, 'reports'])
    ->name('chef.reports');

Route::get('/messages', [DashboardController::class, 'messages'])
    ->name('chef.messages');

/*



/*
|--------------------------------------------------------------------------
| Pages Superviseur (DashboardController)
|--------------------------------------------------------------------------
*/


Route::get('/superviseur/tasks', [DashboardController::class, 'tasks'])
    ->name('superviseur.tasks');

Route::get('/superviseur/reports', [DashboardController::class, 'reports'])
    ->name('superviseur.reports');

Route::get('/superviseur/messages', [DashboardController::class, 'messages'])
    ->name('superviseur.messages');
/*

/*
|--------------------------------------------------------------------------
| Pages Equipe  (EquipeController)
|--------------------------------------------------------------------------
*/
Route::get('/equipe', [EquipeController::class, 'index'])
    ->name('equipe');

Route::get('/equipe/membre/{user}', [EquipeController::class, 'show'])
    ->name('equipe.partials.profile');
/*


|--------------------------------------------------------------------------
| Paramètres & Profil Createur (SettingsController & ProfileController)
|--------------------------------------------------------------------------
*/
Route::get('/settings', [SettingsController::class, 'index'])
    ->name('chef.settings');

Route::post('/settings', [SettingsController::class, 'update'])
    ->name('chef.settings.update');

Route::get('/settings/profile', [ProfileController::class, 'index'])
    ->name('chef.profile');

Route::post('/settings/update-bio', [ProfileController::class, 'updateBio'])
    ->name('chef.updateBio');

/*
|--------------------------------------------------------------------------
| Paramètres & Profil Superviseur (SettingsController & ProfileController)
|--------------------------------------------------------------------------
*/
Route::get('/superviseur/settings', [SettingsController::class, 'index'])
    ->name('superviseur.settings');

Route::post('/superviseur/settings', [SettingsController::class, 'update'])
    ->name('superviseur.settings.update');

Route::get('/superviseur/settings/profile', [ProfileController::class, 'index'])
    ->name('superviseur.profile');

Route::post('/superviseur/settings/update-bio', [ProfileController::class, 'updateBio'])
    ->name('superviseur.updateBio');


 /// Pages Taches

Route::middleware('auth')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    Route::post('/tasks/{task}/add-contributor', [TaskController::class, 'addContributor'])->name('tasks.addContributor');
    Route::post('/tasks/{task}/remove-contributor', [TaskController::class, 'removeContributor'])->name('tasks.removeContributor');
    Route::post('/tasks/{task}/contributor-toggle', [TaskController::class, 'toggleContributor'])->name('tasks.toggleContributor');


    Route::post('/tasks/{task}/archive', [TaskController::class, 'archiveTask']) ->name('tasks.archive');

});







