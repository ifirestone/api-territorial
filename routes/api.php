<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Tools\ResponseCodes;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

# Debugger Test
Route::get('/ping', [DebugController::class, 'ping']);

#Profile Login
Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:api', 'verified']], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    #Profile Controller
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'getProfile');
        Route::post('/profile/update', 'update');
        Route::post('/profile/password', 'password');
        Route::delete('/profile/destroy_image', 'destroy');
    });

    #Usuarios Controller
    Route::controller(UserController::class)->group(function () {
        Route::get('/usuarios', 'index');
        Route::get('/usuarios/{usuario}/show', 'show');
        Route::post('/usuarios/store', 'store');
        Route::post('/usuarios/{usuario}/update', 'update');
        Route::delete('/usuarios/{usuario}/delete', 'destroy');
        Route::post('/usuarios/{usuario}/resetpassword', 'resetPassword');
        Route::post('/usuarios/{usuario}/toggle', 'toggle');
    });

    #Roles Controller
    Route::controller(RoleController::class)->group(function () {
        Route::get('/roles', 'roles');
        Route::get('/roles/permisos', 'permisos');
        Route::get('/roles/modulos', 'modulos');
        Route::get('/roles/{role}/show', 'show');
        Route::post('/roles/store', 'store');
        Route::post('/roles/{role}/update', 'update');
        Route::post('/roles/{role}/attachModule', 'attachModule');
        Route::post('/roles/{role}/attachPermission', 'attachPermission');
        Route::delete('/roles/{role}/detachModule', 'detachModule');
        Route::delete('/roles/{role}/detachPermission', 'detachPermission');
        Route::delete('/roles/{role}/delete', 'destroy');
    });
});


//FALL BACK ROUTE FOR NO URL FOUND
Route::fallback(function () {
    return response()->json([
        'status' => 'error', 'message' => 'Incorrect Route or not logged'
    ], ResponseCodes::NOT_FOUND);
});