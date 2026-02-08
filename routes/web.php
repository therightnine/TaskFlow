<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\Auth\OptionalController;
use App\Models\Role;
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
| Page Tarifs (AbonnementController)
|--------------------------------------------------------------------------
*/
Route::get('/tarifs', [AbonnementController::class, 'index_abonnement'])
    ->name('abonnements.index');
Route::post('/abonnements/choisir', [AbonnementController::class, 'choose'])
    ->name('abonnements.choose');


/*
|--------------------------------------------------------------------------
| Authentification (LoginController)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Inscription (RegisterController & OptionalController)
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/register/optional/{user_id}', [OptionalController::class, 'show'])->name('register.optional');
Route::post('/register/optional/{user_id}', [OptionalController::class, 'store'])->name('register.optional.store');



/*
|--------------------------------------------------------------------------
| ALL AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboards selon rôle
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/chef', [DashboardController::class, 'chef'])->name('dashboard.chef');
    Route::get('/dashboard/superviseur', [DashboardController::class, 'supervisor'])->name('dashboard.superviseur');
    Route::get('/dashboard/member', [DashboardController::class, 'member'])->name('dashboard.member');
    Route::get('/dashboard/contributeur', [DashboardController::class, 'contributeur'])->name('dashboard.contributeur');


    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        | Gestion Abonnements (AbonnementController)
        */
        Route::get('/abonnements', [AbonnementController::class, 'gest_abonnement'])
            ->name('abonnements.gest_abonnements');

        Route::get('/abonnements/create', [AbonnementController::class, 'create'])
            ->name('abonnements.create');

        Route::post('/abonnements', [AbonnementController::class, 'store'])
            ->name('abonnements.store');

        Route::get('/abonnements/{abonnement}/edit', [AbonnementController::class, 'edit'])
            ->name('abonnements.edit');

        Route::put('/abonnements/{abonnement}', [AbonnementController::class, 'update'])
            ->name('abonnements.update');

        Route::delete('/abonnements/{abonnement}', [AbonnementController::class, 'destroy'])
            ->name('abonnements.destroy');


        /*
        | Gestion Rôles (RoleController)
        */
        Route::get('/roles', [RoleController::class, 'gest_roles'])
            ->name('roles.gest_roles');

        Route::get('/roles/create', [RoleController::class, 'create'])
            ->name('roles.create');

        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store');

        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
            ->name('roles.edit');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy');


        /*
        | Gestion Utilisateurs (AdminUsersController)
        */
        Route::get('/utilisateurs', [AdminUsersController::class, 'index'])
            ->name('utilisateurs.index');

        Route::put('/users/{user}', [AdminUsersController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [AdminUsersController::class, 'destroy'])
            ->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | CREATEUR & CONTRIBITEUR & SUPERVISEUR AREA
    |--------------------------------------------------------------------------
    */

        /*
        | Projets (ProjetController)
        */
        Route::get('/projects', [ProjetController::class, 'index'])->name('projects.index');
        Route::get('/projects/create', [ProjetController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjetController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjetController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjetController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjetController::class, 'destroy'])->name('projects.destroy');

        Route::post('/projects/{project}/archive', [ProjetController::class, 'archive'])->name('projects.archive');
        Route::post('/projects/{project}/favorite', [ProjetController::class, 'toggleFavorite'])->name('projects.favorite');
        Route::post('/projects/{project}/add-supervisor', [ProjetController::class, 'addSupervisor'])->name('projects.updateSupervisor');
        Route::post('/projects/{project}/add-contributor', [ProjetController::class, 'addContributor'])->name('projects.addContributor');
        Route::post('/projects/{project}/contributor-toggle', [ProjetController::class, 'toggleContributor']);
        Route::post('/projects/{project}/supervisor-toggle', [ProjetController::class, 'toggleSupervisor']);


        /*
        | Tâches (TaskController)
        */
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

        Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
        Route::post('/tasks/{task}/archive', [TaskController::class, 'archiveTask'])->name('tasks.archive');

        Route::post('/tasks/{task}/add-contributor', [TaskController::class, 'addContributor'])->name('tasks.addContributor');
        Route::post('/tasks/{task}/remove-contributor', [TaskController::class, 'removeContributor'])->name('tasks.removeContributor');
        Route::post('/tasks/{task}/contributor-toggle', [TaskController::class, 'toggleContributor'])->name('tasks.toggleContributor');


        /*
        | Équipe (EquipeController)
        */
        Route::get('/equipe', [EquipeController::class, 'index'])->name('equipe');
        Route::get('/equipe/membre/{user}', [EquipeController::class, 'show'])->name('equipe.partials.profile');
    

    /*
    |--------------------------------------------------------------------------
    | ALL ROLES SHARED AREA
    |--------------------------------------------------------------------------
    */
        /*
        | Settings & Profile (SettingsController & ProfileController)
        */
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/update-bio', [ProfileController::class, 'updateBio'])->name('profile.updateBio');

});
