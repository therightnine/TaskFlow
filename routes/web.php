<?php

use Illuminate\Support\Facades\Route;
use App\Models\Projet;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
})->name('home');

// Login routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes Dashboard avec auth
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard/chef', [DashboardController::class, 'chef'])->name('dashboard.chef');
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/supervisor', [DashboardController::class, 'supervisor'])->name('dashboard.supervisor');
    Route::get('/dashboard/member', [DashboardController::class, 'member'])->name('dashboard.member');
});

// Routes projets (CRUD) avec auth
Route::middleware(['auth'])->group(function() {
    Route::get('/projects', [ProjetController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjetController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjetController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjetController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjetController::class, 'update'])->name('projects.update');
    Route::post('/projects/{project}/archive', [ProjetController::class, 'archive'])->name('projects.archive');
    Route::delete('/projects/{project}', [ProjetController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/favorite', [ProjetController::class, 'toggleFavorite'])->name('projects.favorite');
    Route::post('/{project}/add-supervisor', [ProjetController::class, 'addSupervisor'])->name('projects.updateSupervisor');
});

// Préfixe chef
Route::prefix('chef')->middleware(['auth'])->group(function() {
    Route::get('/projects', [DashboardController::class, 'projects'])->name('chef.projects');
    Route::get('/team', [DashboardController::class, 'team'])->name('chef.team');
    Route::get('/tasks', [DashboardController::class, 'index'])->name('chef.tasks');
    Route::get('/reports', [DashboardController::class, 'index'])->name('chef.reports');
    Route::get('/messages', [DashboardController::class, 'index'])->name('chef.messages');

    Route::get('/settings', [SettingsController::class, 'index'])->name('chef.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('chef.settings.update');

    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('chef.profile');
    Route::post('/settings/update-bio', [ProfileController::class, 'updateBio'])->name('chef.updateBio');
});
