<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Sheet\SheetController;
use App\Http\Controllers\User\UserController;
use \App\Http\Controllers\Group\GroupController;
use \App\Http\Controllers\WeeklyDeportation\WeeklyDeportationController;
use \App\Http\Controllers\DeportationRules\DeportationRuleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login-post');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('lang/{locale}', function ($locale) {
            if (!in_array($locale, ['en', 'ar'])) {
                abort(400);
            }

            session(['locale' => $locale]);
            app()->setLocale($locale);

            return redirect()->back();
        })->name('lang.switch');


    Route::middleware(['auth'])->group(function () {

            Route::get('/dashboard', function () {
                    return view('dashboard.index');
                });

                //5321000

            Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::post('/users/{group}/create', [UserController::class, 'store'])->name('create');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');

            });


            Route::prefix('admins')->name('admins.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');         // List all admins
                Route::get('/create', [UserController::class, 'createAdmin'])->name('create'); // Show create form
                Route::post('/', [UserController::class, 'storeAdmin'])->name('store');        // Store new admin
                Route::get('/{admin}', [UserController::class, 'showAdmin'])->name('show');    // Show single admin
                Route::get('/{admin}/edit', [UserController::class, 'editAdmin'])->name('edit');   // Edit form
                Route::put('/{admin}', [UserController::class, 'updateAdmin'])->name('update');    // Update admin
            });


            Route::resource('groups', GroupController::class);

            Route::get('/groups/{group}/sheets', [SheetController::class, 'groupSheets'])
            ->name('groups.sheets');

            Route::post('/groups/{group}/users/add', [GroupController::class, 'addUser'])->name('groups.users.add');

            Route::post('/sheets/{sheet}/users/{user}/increment', [SheetController::class, 'increment'])->name('sheet.increment');
            Route::post('/sheets/{sheet}/addmerged', [SheetController::class, 'addmerged'])->name('sheet.addmerged');


            Route::post('/sheets/{sheet}/users/undo', [SheetController::class, 'undo'])->name('sheet.undo');
            Route::post('/sheets/{sheet}/users/redo', [SheetController::class, 'redo'])->name('sheet.redo');
            Route::post('/sheets/{sheet}/users/{user}/manual-update', [SheetController::class, 'manualUpdate'])
            ->name('sheet.manualUpdate');

            Route::post('/sheets/{sheet}/users/store', [SheetController::class, 'storeUser'])->name('sheets.users.store');

            Route::post('/sheets/{sheet}/update-field', [SheetController::class, 'updateField']);

            Route::post('/sheets/{sheet}/users/{user}/update-note', [SheetController::class, 'updateNote']);

            Route::post('/sheets/{sheet}/deport', [SheetController::class, 'deport'])->name('sheets.deport');

            Route::post('/sheets', [SheetController::class, 'store'])->name('sheets.store');

            Route::post('/sheets/{sheet}/users/{user}/note-type', [SheetController::class, 'updateNoteType'])
                ->name('sheets.users.note-type');

            Route::get('/weekly-deportations', [WeeklyDeportationController::class, 'index'])->name('weekly_deportations.index');
            Route::delete('/deportations/{deportation}', [WeeklyDeportationController::class, 'destroy'])->name('deportations.destroy');


            Route::get('/general-settings', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'edit'])->name('general_settings.edit');
            // Route::post('/general-settings', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'update'])->name('general_settings.update');
//  Route::get('/general-settings', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'index'])->name('general_settings.index');
            Route::post('/general-settings', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'store'])->name('general_settings.store');
            Route::post('/general-settings/update', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'update'])->name('general_settings.update');
            Route::delete('/general-settings/{id}', [\App\Http\Controllers\GeneralSetting\GeneralSettingController::class, 'delete'])->name('general_settings.delete');



            Route::delete('/sheets/{sheet}/users/{user}', [SheetController::class, 'removeUser'])->name('sheets.users.remove');

            Route::prefix('deportation-rules')->name('admin.deportation_rules.')->group(function () {
                Route::get('/', [DeportationRuleController::class, 'index'])->name('index');
                Route::post('/', [DeportationRuleController::class, 'store'])->name('store');
                Route::post('/{rule}/update', [DeportationRuleController::class, 'update'])->name('update');
                Route::delete('/{rule}', [DeportationRuleController::class, 'destroy'])->name('destroy');
            });


    });

        Route::get('/user/deportation', [App\Http\Controllers\User\UserDeportationController::class, 'showForm'])->name('user.deportation.form');
        Route::post('/user/deportation', [App\Http\Controllers\User\UserDeportationController::class, 'view'])->name('user.deportation.view');
        Route::get('/sheets/{sheet}/users-table', [SheetController::class, 'loadSheetUsers'])
            ->name('sheets.usersTable');

