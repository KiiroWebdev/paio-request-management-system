<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceFieldController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('organizations', OrganizationController::class);
    Route::resource('contacts', ContactController::class);
    // Request Wizard
    Route::get('/requests/wizard', [DocumentRequestController::class, 'wizard'])
        ->name('requests.wizard');

    // Request CRUD
    Route::resource('requests', DocumentRequestController::class);

    Route::resource('services', ServiceController::class);

    Route::prefix('services/{service}')
    ->name('services.')
    ->group(function () {

        Route::resource('fields', ServiceFieldController::class);

    });
});

require __DIR__.'/auth.php';