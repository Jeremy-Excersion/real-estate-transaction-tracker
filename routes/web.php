<?php

use App\Models\User;
use Inertia\Inertia;
use App\Models\SaleSource;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/sales')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/report/{id}', [SaleController::class, 'report'])->name('sales.report');
        Route::post('/export', [SaleController::class, 'export'])->name('sales.export');
        Route::middleware('role:admin')->group(function () {
            Route::get('/edit/{id}', [SaleController::class, 'edit'])->name('sales.edit');
            Route::post('/edit/{id}', [SaleController::class, 'update'])->name('sales.update');
            Route::get('/create', function () {
                $users = User::orderBy('name')->get();
                $sources = SaleSource::select('name')->distinct()->get();
                return Inertia::render('Sales/Create', [
                    'users' => $users,
                    'sources' => $sources,
                ]);
            })->name('create');
            Route::post('/create', [SaleController::class, 'create'])->name('sales.create');
        });
    });
});

Route::middleware('role:admin')->prefix('admin')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Admin/Index');
    })->name('admin');

    Route::get('/users', function () {
        $users = User::with('roles')->get();
        return Inertia::render('Admin/Users/Index', ['users' => $users]);
    })->name('admin.users');
});

require __DIR__ . '/auth.php';
