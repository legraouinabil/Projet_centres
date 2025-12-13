<?php

use App\Http\Livewire\AuthLogin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes


Route::get('/login', function () {
    return view('auth.login-livewire');
})->name('login');

// Authentication routes
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // User routes
    Route::middleware(['role:user'])->group(function () {
        Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/profil', [HomeController::class, 'profile'])->name('profile');
        Route::get('/associations', [HomeController::class, 'associationManager'])->name('associations');
        Route::get('/centres', [HomeController::class, 'centreManager'])->name('centres');
        Route::get('/dossiers', [HomeController::class, 'dossierManager'])->name('dossiers');
    });
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/dossiers', [HomeController::class, 'dossierManager'])->name('dossiers');
        Route::get('/users', [HomeController::class, 'userManager'])->name('admin.users');
        Route::get('/settings',  [HomeController::class, 'settingsManager'])->name('admin.settings');
        //  Route::get('/reports', [HomeController::class, 'reportsManager'])->name('admin.reports');
        Route::get('/associations', [HomeController::class, 'associationManager'])->name('associations');

        Route::get('/centres', [HomeController::class, 'centreManager'])->name('centres');
        Route::get('/gestionnaires', [HomeController::class, 'gestionnaire'])->name('gestionnaires');
        Route::get('/ressources-humaines', [HomeController::class, 'ressourcH'])->name('ressources-humaines');
        Route::get('/ressources-financieres', [HomeController::class, 'ressourceF'])->name('ressources-financieres');
        Route::get('/impacts', [HomeController::class, 'impact'])->name('impacts');
        Route::get('/reports', [HomeController::class, 'reports'])->name('reports');
        Route::get('/profil', [HomeController::class, 'profile'])->name('profile');

        Route::get('/reports/export/pdf', [ReportExportController::class, 'exportPdf'])
            ->name('reports.export.pdf');

        Route::get('/reports/export/excel', [ReportExportController::class, 'exportExcel'])
            ->name('reports.export.excel');
    });

    // Manager routes
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'managerDashboard'])->name('manager.dashboard');
    });
});
