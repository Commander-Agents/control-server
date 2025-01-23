<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [CompanyController::class, 'dashboard'])->middleware(['verified'])->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    Route::prefix('agents')->group(function () {
        Route::view('/', 'pages.agents.index')->name('agents.index');
        Route::view('/task/{agentId?}', 'pages.agents.execute-playbook')->name('agents.execute-playbook');
    });

    Route::prefix('tasks')->group(function () {
        Route::view('/', 'pages.tasks.index')->name('tasks.index');
    });
});


require __DIR__.'/auth.php';
