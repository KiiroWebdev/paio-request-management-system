<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceFieldController;
use App\Http\Controllers\RequestWizardController;
use App\Http\Controllers\ServiceFieldOptionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::resource('services', ServiceController::class);
    Route::resource('organizations', OrganizationController::class);
    Route::resource('contacts', ContactController::class);

    /*
|--------------------------------------------------------------------------
| Request Management
|--------------------------------------------------------------------------
*/
Route::get(
    '/requests/incoming',
    [DocumentRequestController::class, 'incoming']
)->name('requests.incoming');

Route::get(
    '/requests/ongoing',
    [DocumentRequestController::class, 'ongoing']
)->name('requests.ongoing');

Route::get(
    '/requests/completed',
    [DocumentRequestController::class, 'completed']
)->name('requests.completed');

Route::get(
    '/requests/archive',
    [DocumentRequestController::class, 'archiveIndex']
)->name('requests.archive.index');

Route::post(
    '/requests/{documentRequest}/restore',
    [DocumentRequestController::class, 'restore']
)->name('requests.restore');

Route::resource('requests', DocumentRequestController::class)
    ->parameters([
        'requests' => 'documentRequest',
    ]);
    
Route::get(
    '/requests/{documentRequest}/print',
    [DocumentRequestController::class, 'print']
)->name('requests.print');

Route::post(
    '/requests/{documentRequest}/assign',
    [DocumentRequestController::class, 'assign']
)->name('requests.assign');

Route::post(
    '/requests/{documentRequest}/start',
    [DocumentRequestController::class, 'start']
)->name('requests.start');

Route::post(
    '/requests/{documentRequest}/complete',
    [DocumentRequestController::class, 'complete']
)->name('requests.complete');

Route::post(
    '/requests/{documentRequest}/archive',
    [DocumentRequestController::class, 'archive']
)->name('requests.archive');
    /*
    |--------------------------------------------------------------------------
    | Request Wizard
    |--------------------------------------------------------------------------
    */
    Route::get('/new-request', [RequestWizardController::class, 'services'])
    ->name('request-wizard.services');

    Route::get('/request/{service:slug}', [RequestWizardController::class, 'create'])
        ->name('request-wizard.create');

    Route::post('/request/{service:slug}', [RequestWizardController::class, 'store'])
        ->name('request-wizard.store');

    

    /*
    |--------------------------------------------------------------------------
    | Service Field Designer
    |--------------------------------------------------------------------------
    */

   Route::prefix('services/{service}')
    ->name('services.fields.')
    ->group(function () {

        // List Fields
        Route::get('/fields', [ServiceFieldController::class, 'index'])
            ->name('index');

        // Create Field
        Route::get('/fields/create', [ServiceFieldController::class, 'create'])
            ->name('create');

        Route::post('/fields', [ServiceFieldController::class, 'store'])
            ->name('store');

        // Edit Field
        Route::get('/fields/{field}/edit', [ServiceFieldController::class, 'edit'])
            ->name('edit');

        Route::put('/fields/{field}', [ServiceFieldController::class, 'update'])
            ->name('update');

        // Delete Field
        Route::delete('/fields/{field}', [ServiceFieldController::class, 'destroy'])
            ->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | Service Field Options
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/fields/{field}/options',
            [ServiceFieldOptionController::class, 'index']
        )->name('options.index');

        Route::post(
            '/fields/{field}/options',
            [ServiceFieldOptionController::class, 'store']
        )->name('options.store');

        Route::get(
    '/fields/{field}/options/{option}/edit',
    [ServiceFieldOptionController::class, 'edit']
)->name('options.edit');

        Route::put(
            '/fields/{field}/options/{option}',
            [ServiceFieldOptionController::class, 'update']
        )->name('options.update');

        Route::delete(
            '/fields/{field}/options/{option}',
            [ServiceFieldOptionController::class, 'destroy']
        )->name('options.destroy');

    });
});

require __DIR__.'/auth.php';