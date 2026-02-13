<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backoffice\AuthController;
use App\Http\Controllers\Backoffice\UserController;
use App\Http\Controllers\Backoffice\ProfileController;
use App\Http\Controllers\Backoffice\VehicleBrandController;
use App\Http\Controllers\Backoffice\VehicleModelController;
use App\Http\Controllers\Backoffice\VehicleController;
use App\Http\Controllers\Backoffice\AgencyController;
use App\Http\Controllers\Backoffice\AgentController;
use App\Http\Controllers\Backoffice\ClientController;
use App\Http\Controllers\Backoffice\AgencySubscriptionController;
use App\Http\Controllers\Backoffice\RoleController;
use App\Http\Controllers\Backoffice\PermissionController;
use App\Http\Controllers\Backoffice\RolesPermissionsController;
use App\Http\Controllers\Backoffice\Vehicles\VignetteController;
use App\Http\Controllers\Backoffice\Vehicles\InsuranceController;
use App\Http\Controllers\Backoffice\Vehicles\TechnicalCheckController;
use App\Http\Controllers\Backoffice\Vehicles\OilChangeController;
use App\Http\Controllers\Backoffice\Vehicles\ControlController;
use App\Http\Controllers\Backoffice\Vehicles\ControlItemController;

Route::prefix('backoffice')->name('backoffice.')->group(function () {

    Route::middleware('guest:backoffice')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/login/demo', [AuthController::class, 'demoLogin'])->name('login.demo');
    });

    Route::middleware(['auth:backoffice'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::view('/dashboard', 'backoffice.index')
            ->name('dashboard')
            ->middleware('role:super-admin|admin|manager,backoffice');

        // ==================== USERS ====================
        Route::prefix('users')->name('users.')
            ->middleware('role:super-admin|admin,backoffice')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}', [UserController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            });

        // ==================== CLIENTS ====================
        Route::prefix('clients')->name('clients.')
            ->middleware('role:super-admin|admin|manager,backoffice')
            ->group(function () {
                Route::get('/', [ClientController::class, 'index'])->name('index');
                Route::get('/create', [ClientController::class, 'create'])->name('create');
                Route::post('/', [ClientController::class, 'store'])->name('store');
                Route::get('/{client}', [ClientController::class, 'show'])->name('show');
                Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
                Route::put('/{client}', [ClientController::class, 'update'])->name('update');
                Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');
            });

        // ==================== PROFILE ====================
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit')
            ->middleware('role:super-admin|admin|manager,backoffice');
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update')
            ->middleware('role:super-admin|admin|manager,backoffice');

        // ==================== AGENCIES ====================
        Route::prefix('agencies')->name('agencies.')
            ->middleware('role:super-admin|admin,backoffice')
            ->group(function () {
                Route::get('/', [AgencyController::class, 'index'])->name('index');
                Route::get('/create', [AgencyController::class, 'create'])->name('create');
                Route::post('/', [AgencyController::class, 'store'])->name('store');
                Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
                Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
                Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
                Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
            });

        // ==================== AGENTS ====================
        Route::prefix('agents')->name('agents.')
            ->middleware('role:super-admin|admin|manager,backoffice')
            ->group(function () {
                Route::get('/', [AgentController::class, 'index'])->name('index');
                Route::get('/create', [AgentController::class, 'create'])->name('create');
                Route::post('/', [AgentController::class, 'store'])->name('store');
                Route::get('/{agent}', [AgentController::class, 'show'])->name('show');
                Route::get('/{agent}/edit', [AgentController::class, 'edit'])->name('edit');
                Route::put('/{agent}', [AgentController::class, 'update'])->name('update');
                Route::delete('/{agent}', [AgentController::class, 'destroy'])->name('destroy');
            });

        // ==================== AGENCY SUBSCRIPTIONS ====================
        Route::prefix('agency-subscriptions')->name('agency-subscriptions.')
            ->middleware('role:super-admin|admin,backoffice')
            ->group(function () {
                Route::get('/', [AgencySubscriptionController::class, 'index'])->name('index');
                Route::get('/create', [AgencySubscriptionController::class, 'create'])->name('create');
                Route::post('/', [AgencySubscriptionController::class, 'store'])->name('store');
                Route::get('/{agencySubscription}', [AgencySubscriptionController::class, 'show'])->name('show');
                Route::get('/{agencySubscription}/edit', [AgencySubscriptionController::class, 'edit'])->name('edit');
                Route::put('/{agencySubscription}', [AgencySubscriptionController::class, 'update'])->name('update');
                Route::delete('/{agencySubscription}', [AgencySubscriptionController::class, 'destroy'])->name('destroy');
            });

        // ==================== ROLES & PERMISSIONS ====================
        Route::middleware('role:super-admin|admin,backoffice')->group(function () {
            Route::get('/roles-permissions', [RolesPermissionsController::class, 'index'])->name('roles-permissions.index');

            Route::prefix('roles')->name('roles.')->group(function () {
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::put('/{role}', [RoleController::class, 'update'])->name('update');
                Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('permissions')->name('permissions.')->group(function () {
                Route::post('/', [PermissionController::class, 'store'])->name('store');
                Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
                Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
            });
        });

        // ==================== VEHICLE BRANDS ====================
        Route::prefix('vehicle-brands')->name('vehicle-brands.')
            ->middleware('role:super-admin|admin|manager,backoffice')
            ->group(function () {
                Route::get('/', [VehicleBrandController::class, 'index'])->name('index');
                Route::get('/create', [VehicleBrandController::class, 'create'])->name('create');
                Route::post('/', [VehicleBrandController::class, 'store'])->name('store');
                Route::get('/{vehicleBrand}', [VehicleBrandController::class, 'show'])->name('show');
                Route::get('/{vehicleBrand}/edit', [VehicleBrandController::class, 'edit'])->name('edit');
                Route::put('/{vehicleBrand}', [VehicleBrandController::class, 'update'])->name('update');
                Route::delete('/{vehicleBrand}', [VehicleBrandController::class, 'destroy'])->name('destroy');
            });

        // ==================== VEHICLE MODELS ====================
        Route::prefix('vehicle-models')->name('vehicle-models.')
            ->middleware('role:super-admin|admin|manager,backoffice')
            ->group(function () {
                Route::get('/', [VehicleModelController::class, 'index'])->name('index');
                Route::get('/create', [VehicleModelController::class, 'create'])->name('create');
                Route::post('/', [VehicleModelController::class, 'store'])->name('store');
                Route::get('/{vehicleModel}', [VehicleModelController::class, 'show'])->name('show');
                Route::get('/{vehicleModel}/edit', [VehicleModelController::class, 'edit'])->name('edit');
                Route::put('/{vehicleModel}', [VehicleModelController::class, 'update'])->name('update');
                Route::delete('/{vehicleModel}', [VehicleModelController::class, 'destroy'])->name('destroy');
            });

        // ==================== VEHICLES ====================
        Route::prefix('vehicles')->name('vehicles.')
            ->middleware('role:super-admin|admin|manager,backoffice')
            ->group(function () {

                Route::delete('/bulk-destroy', [VehicleController::class, 'bulkDestroy'])->name('bulkDestroy');

                Route::get('/', [VehicleController::class, 'index'])->name('index');
                Route::post('/check-duplicate', [VehicleController::class, 'checkDuplicate'])->name('check-duplicate');
                Route::get('/create', [VehicleController::class, 'create'])->name('create');
                Route::post('/', [VehicleController::class, 'store'])->name('store');
                Route::get('/{vehicle}', [VehicleController::class, 'show'])->name('show');
                Route::get('/{vehicle}/edit', [VehicleController::class, 'edit'])->name('edit');
                Route::put('/{vehicle}', [VehicleController::class, 'update'])->name('update');
                Route::delete('/{vehicle}', [VehicleController::class, 'destroy'])->name('destroy');

                /**
                 * ==================== VEHICLE VIGNETTES ====================
                 * ORDER IS CRITICAL:
                 * 1. GLOBAL ROUTES (no {vehicle}) - MUST COME FIRST
                 * 2. VEHICLE-SPECIFIC ROUTES (with {vehicle}) - MUST COME SECOND
                 */

                // ✅ 1. GLOBAL VIGNETTES ROUTES (NO VEHICLE PARAMETER)
                Route::prefix('vignettes')->name('vignettes.')->group(function () {
                    Route::get('/create', [VignetteController::class, 'create'])->name('create');
                    Route::post('/', [VignetteController::class, 'store'])->name('store');
                });

                // ✅ 2. VEHICLE-SPECIFIC VIGNETTES ROUTES (WITH VEHICLE PARAMETER)
                Route::prefix('{vehicle}/vignettes')->name('vignettes.')->group(function () {
                    Route::get('/', [VignetteController::class, 'index'])->name('index');
                    Route::get('/{vignette}', [VignetteController::class, 'show'])->name('show');
                    Route::get('/{vignette}/edit', [VignetteController::class, 'edit'])->name('edit');
                    Route::put('/{vignette}', [VignetteController::class, 'update'])->name('update');
                    Route::delete('/{vignette}', [VignetteController::class, 'destroy'])->name('destroy');
                    // NO '/create' ROUTE HERE - IT'S IN THE GLOBAL GROUP
                });

                /**
                 * ==================== VEHICLE INSURANCES ====================
                 * ORDER IS CRITICAL:
                 * 1. GLOBAL ROUTES (no {vehicle}) - MUST COME FIRST
                 * 2. VEHICLE-SPECIFIC ROUTES (with {vehicle}) - MUST COME SECOND
                 */

                // ✅ 1. GLOBAL INSURANCES ROUTES (NO VEHICLE PARAMETER)
                Route::prefix('insurances')->name('insurances.')->group(function () {
                    Route::get('/create', [InsuranceController::class, 'create'])->name('create');
                    Route::post('/', [InsuranceController::class, 'store'])->name('store');
                });

                // ✅ 2. VEHICLE-SPECIFIC INSURANCES ROUTES (WITH VEHICLE PARAMETER)
                Route::prefix('{vehicle}/insurances')->name('insurances.')->group(function () {
                    Route::get('/', [InsuranceController::class, 'index'])->name('index');
                    Route::get('/{insurance}', [InsuranceController::class, 'show'])->name('show');
                    Route::get('/{insurance}/edit', [InsuranceController::class, 'edit'])->name('edit');
                    Route::put('/{insurance}', [InsuranceController::class, 'update'])->name('update');
                    Route::delete('/{insurance}', [InsuranceController::class, 'destroy'])->name('destroy');
                    // NO '/create' ROUTE HERE - IT'S IN THE GLOBAL GROUP
                });

                /**
                 * ==================== VEHICLE OIL CHANGES ====================
                 * ORDER IS CRITICAL:
                 * 1. GLOBAL ROUTES (no {vehicle}) - MUST COME FIRST
                 * 2. VEHICLE-SPECIFIC ROUTES (with {vehicle}) - MUST COME SECOND
                 */

                // ✅ 1. GLOBAL OIL CHANGES ROUTES (NO VEHICLE PARAMETER)
                Route::prefix('oil-changes')->name('oil-changes.')->group(function () {
                    Route::get('/create', [OilChangeController::class, 'create'])->name('create');
                    Route::post('/', [OilChangeController::class, 'store'])->name('store');
                });

                // ✅ 2. VEHICLE-SPECIFIC OIL CHANGES ROUTES (WITH VEHICLE PARAMETER)
                Route::prefix('{vehicle}/oil-changes')->name('oil-changes.')->group(function () {
                    Route::get('/', [OilChangeController::class, 'index'])->name('index');
                    Route::get('/{oilChange}', [OilChangeController::class, 'show'])->name('show');
                    Route::get('/{oilChange}/edit', [OilChangeController::class, 'edit'])->name('edit');
                    Route::put('/{oilChange}', [OilChangeController::class, 'update'])->name('update');
                    Route::delete('/{oilChange}', [OilChangeController::class, 'destroy'])->name('destroy');
                    // NO '/create' ROUTE HERE - IT'S IN THE GLOBAL GROUP
                });

// ==================== VEHICLE TECHNICAL CHECKS ====================
// ✅ GLOBAL CREATE ROUTES (NO VEHICLE PARAMETER) - MUST COME FIRST
Route::prefix('technical-checks')->name('technical-checks.')->group(function () {
    Route::get('/create', [TechnicalCheckController::class, 'create'])->name('create');
    Route::post('/', [TechnicalCheckController::class, 'store'])->name('store');
});

// ✅ VEHICLE-SPECIFIC ROUTES (WITH VEHICLE PARAMETER) - MUST COME SECOND
Route::prefix('{vehicle}/technical-checks')->name('technical-checks.')->group(function () {
    Route::get('/', [TechnicalCheckController::class, 'index'])->name('index');
    Route::get('/{technicalCheck}', [TechnicalCheckController::class, 'show'])->name('show');
    Route::get('/{technicalCheck}/edit', [TechnicalCheckController::class, 'edit'])->name('edit');
    Route::put('/{technicalCheck}', [TechnicalCheckController::class, 'update'])->name('update');
    Route::delete('/{technicalCheck}', [TechnicalCheckController::class, 'destroy'])->name('destroy');
    // NO '/create' ROUTE HERE - IT'S IN THE GLOBAL GROUP
});

                // ==================== VEHICLE CONTROLS ====================
                Route::prefix('{vehicle}/controls')->name('controls.')->group(function () {
                    Route::get('/', [ControlController::class, 'index'])->name('index');
                    Route::get('/create', [ControlController::class, 'create'])->name('create');
                    Route::post('/', [ControlController::class, 'store'])->name('store');
                    Route::get('/{control}', [ControlController::class, 'show'])->name('show');
                    Route::get('/{control}/edit', [ControlController::class, 'edit'])->name('edit');
                    Route::put('/{control}', [ControlController::class, 'update'])->name('update');
                    Route::delete('/{control}', [ControlController::class, 'destroy'])->name('destroy');

                    // CONTROL ITEMS
                    Route::prefix('{control}/items')->name('items.')->group(function () {
                        Route::get('/', [ControlItemController::class, 'index'])->name('index');
                        Route::get('/create', [ControlItemController::class, 'create'])->name('create');
                        Route::post('/', [ControlItemController::class, 'store'])->name('store');
                        Route::get('/{item}', [ControlItemController::class, 'show'])->name('show');
                        Route::get('/{item}/edit', [ControlItemController::class, 'edit'])->name('edit');
                        Route::put('/{item}', [ControlItemController::class, 'update'])->name('update');
                        Route::delete('/{item}', [ControlItemController::class, 'destroy'])->name('destroy');
                    });
                });
            }); // END VEHICLES GROUP
    }); // END AUTH GROUP
}); // END BACKOFFICE PREFIX