<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\RequestWizardController;
use App\Http\Controllers\ServiceFieldController;
use App\Http\Controllers\ServiceFieldOptionController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AttachmentDeletionRequestController;
use App\Http\Controllers\RequestActionRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestApprovalController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated + Active Users
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Users — Admin Only
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserController::class)
        ->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ])
        ->middleware('admin');

    Route::patch(
        '/users/{user}/toggle-status',
        [UserController::class, 'toggleStatus']
    )
        ->name('users.toggle-status')
        ->middleware('admin');


    /*
    |--------------------------------------------------------------------------
    | Master Data — Admin Only
    |--------------------------------------------------------------------------
    */

    Route::middleware('admin')->group(function () {

    

        Route::resource('services', ServiceController::class);

        Route::resource('organizations', OrganizationController::class);

        Route::resource('contacts', ContactController::class);

        Route::get('/request-approvals', [RequestApprovalController::class, 'index'])
    ->name('request-approvals.index');

        Route::post(
    '/requests/{documentRequest}/approve',
    [RequestApprovalController::class, 'approve']
)->name('requests.approve');

Route::post(
    '/requests/{documentRequest}/decline',
    [RequestApprovalController::class, 'decline']
)->name('requests.decline');

Route::post(
    '/requests/{documentRequest}/cancel',
    [RequestApprovalController::class, 'cancel']
)->name('requests.cancel');

Route::post(
    '/requests/{documentRequest}/restore-for-approval',
    [RequestApprovalController::class, 'restoreForApproval']
)->name('requests.restore-for-approval');

    });


    /*
    |--------------------------------------------------------------------------
    | Service Field Designer — Admin Only
    |--------------------------------------------------------------------------
    */

    Route::prefix('services/{service}')
        ->name('services.fields.')
        ->middleware('admin')
        ->group(function () {

            // Fields
            Route::get(
                '/fields',
                [ServiceFieldController::class, 'index']
            )->name('index');

            Route::get(
                '/fields/create',
                [ServiceFieldController::class, 'create']
            )->name('create');

            Route::post(
                '/fields',
                [ServiceFieldController::class, 'store']
            )->name('store');

            Route::get(
                '/fields/{field}/edit',
                [ServiceFieldController::class, 'edit']
            )->name('edit');

            Route::put(
                '/fields/{field}',
                [ServiceFieldController::class, 'update']
            )->name('update');

            Route::delete(
                '/fields/{field}',
                [ServiceFieldController::class, 'destroy']
            )->name('destroy');


            // Field Options
            Route::get(
                '/fields/{field}/options',
                [ServiceFieldOptionController::class, 'index']
            )->name('options.index');

            Route::post(
                '/fields/{field}/options',
                [ServiceFieldOptionController::class, 'store']
            )->name('options.store');

            Route::put(
                '/fields/{field}/options/{option}',
                [ServiceFieldOptionController::class, 'update']
            )->name('options.update');

            Route::delete(
                '/fields/{field}/options/{option}',
                [ServiceFieldOptionController::class, 'destroy']
            )->name('options.destroy');

        });


    /*
    |--------------------------------------------------------------------------
    | Request Wizard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/request-wizard/services',
        [RequestWizardController::class, 'services']
    )->name('request-wizard.services');

    Route::get(
        '/request-wizard/{service}',
        [RequestWizardController::class, 'create']
    )->name('request-wizard.create');

    Route::post(
        '/request-wizard/{service}',
        [RequestWizardController::class, 'store']
    )->name('request-wizard.store');


    /*
    |--------------------------------------------------------------------------
    | Document Requests
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/requests',
        [DocumentRequestController::class, 'index']
    )->name('requests.index');

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


    /*
    |--------------------------------------------------------------------------
    | Request Creation
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/requests/create',
        [DocumentRequestController::class, 'create']
    )
        ->name('requests.create')
        ->middleware('admin');

    Route::post('/requests/{service}', [RequestWizardController::class, 'store'])
    ->name('requests.store');


    /*
    |--------------------------------------------------------------------------
    | Individual Request
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/requests/{documentRequest}',
        [DocumentRequestController::class, 'show']
    )->name('requests.show');

    Route::get(
        '/requests/{documentRequest}/edit',
        [DocumentRequestController::class, 'edit']
    )
        ->name('requests.edit')
        ->middleware('admin');

    Route::put(
        '/requests/{documentRequest}',
        [DocumentRequestController::class, 'update']
    )
        ->name('requests.update')
        ->middleware('admin');

    Route::delete(
        '/requests/{documentRequest}',
        [DocumentRequestController::class, 'destroy']
    )
        ->name('requests.destroy')
        ->middleware('admin');

    Route::get(
        '/requests/{documentRequest}/print',
        [DocumentRequestController::class, 'print']
    )->name('requests.print');


    /*
    |--------------------------------------------------------------------------
    | Request Workflow
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Direct Archive / Restore
    |--------------------------------------------------------------------------
    |
    | Only Admin can directly archive or restore.
    |
    */

    Route::post(
        '/requests/{documentRequest}/archive',
        [DocumentRequestController::class, 'archive']
    )
        ->name('requests.archive')
        ->middleware('admin');

    Route::post(
        '/requests/{documentRequest}/restore',
        [DocumentRequestController::class, 'restore']
    )
        ->name('requests.restore')
        ->middleware('admin');


    /*
    |--------------------------------------------------------------------------
    | Archive / Restore Requests
    |--------------------------------------------------------------------------
    |
    | Staff + Admin can submit requests.
    | Only Admin can approve or reject.
    |
    */

    Route::post(
        '/requests/{documentRequest}/action-request',
        [RequestActionRequestController::class, 'store']
    )->name('request-action-requests.store');


    Route::middleware('admin')->group(function () {

        Route::get(
            '/request-action-requests',
            [RequestActionRequestController::class, 'index']
        )->name('request-action-requests.index');

        Route::post(
            '/request-action-requests/{actionRequest}/approve',
            [RequestActionRequestController::class, 'approve']
        )->name('request-action-requests.approve');

        Route::post(
            '/request-action-requests/{actionRequest}/reject',
            [RequestActionRequestController::class, 'reject']
        )->name('request-action-requests.reject');

    });


    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/requests/{documentRequest}/attachments',
        [AttachmentController::class, 'store']
    )->name('requests.attachments.store');

    Route::get(
        '/attachments/{attachment}',
        [AttachmentController::class, 'show']
    )->name('attachments.show');

    Route::get(
        '/attachments/{attachment}/download',
        [AttachmentController::class, 'download']
    )->name('attachments.download');


    /*
    |--------------------------------------------------------------------------
    | Direct Attachment Delete — Admin Only
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/attachments/{attachment}',
        [AttachmentController::class, 'destroy']
    )
        ->name('attachments.destroy')
        ->middleware('admin');


    /*
    |--------------------------------------------------------------------------
    | Attachment Deletion Requests
    |--------------------------------------------------------------------------
    |
    | Staff + Admin can submit deletion requests.
    | Only Admin can approve or reject.
    |
    */

    Route::post(
        '/attachments/{attachment}/deletion-request',
        [AttachmentDeletionRequestController::class, 'store']
    )->name('attachments.deletion-request.store');


    Route::middleware('admin')->group(function () {

        Route::get(
            '/attachment-deletion-requests',
            [AttachmentDeletionRequestController::class, 'index']
        )->name('attachment-deletion-requests.index');

        Route::post(
            '/attachment-deletion-requests/{deletionRequest}/approve',
            [AttachmentDeletionRequestController::class, 'approve']
        )->name('attachment-deletion-requests.approve');

        Route::post(
            '/attachment-deletion-requests/{deletionRequest}/reject',
            [AttachmentDeletionRequestController::class, 'reject']
        )->name('attachment-deletion-requests.reject');

    });

    // Service Field Options
Route::get(
    '/services/{service}/fields/{field}/options',
    [ServiceFieldOptionController::class, 'index']
)->name('services.fields.options.index');

Route::get(
    '/services/{service}/fields/{field}/options/create',
    [ServiceFieldOptionController::class, 'create']
)->name('services.fields.options.create');

Route::post(
    '/services/{service}/fields/{field}/options',
    [ServiceFieldOptionController::class, 'store']
)->name('services.fields.options.store');

Route::get(
    '/services/{service}/fields/{field}/options/{option}/edit',
    [ServiceFieldOptionController::class, 'edit']
)->name('services.fields.options.edit');

Route::put(
    '/services/{service}/fields/{field}/options/{option}',
    [ServiceFieldOptionController::class, 'update']
)->name('services.fields.options.update');

Route::delete(
    '/services/{service}/fields/{field}/options/{option}',
    [ServiceFieldOptionController::class, 'destroy']
)->name('services.fields.options.destroy');



});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Laravel Breeze authentication routes:
| login, logout, register, password reset, verification, etc.
|
*/

require __DIR__.'/auth.php';