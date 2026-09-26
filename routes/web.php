<?php

use App\Http\Controllers\ComputerController;
use App\Http\Controllers\ComputerIssueController;
use App\Http\Controllers\CbtSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkloadReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Denah Lab (Floor Plan) - Livewire component
    Route::get('/floor-plan', function () {
        return view('floor-plan');
    })->name('floor-plan');

    // Sesi CBT
    Route::resource('sessions', CbtSessionController::class);
    Route::post('/sessions/{session}/start', [CbtSessionController::class, 'start'])->name('sessions.start');
    Route::post('/sessions/{session}/end', [CbtSessionController::class, 'end'])->name('sessions.end');
    Route::post('/sessions/{session}/update-allocation', [CbtSessionController::class, 'updateAllocation'])->name('sessions.update-allocation');

    // Tiket Kerusakan
    Route::resource('issues', ComputerIssueController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('/issues/{issue}/update-status', [ComputerIssueController::class, 'update'])->name('issues.update-status');

    // Komputer (Admin only)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('computers', ComputerController::class)->only(['index', 'edit', 'update']);
        Route::patch('/computers/{computer}/status', [ComputerController::class, 'updateStatus'])->name('computers.update-status');
    });

    // Laporan
    Route::get('/reports', [WorkloadReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [WorkloadReportController::class, 'exportPdf'])->name('reports.export-pdf');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
