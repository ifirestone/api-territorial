<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectorController;
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


Route::controller(ProvinciaController::class)->group(function () {
    Route::get('/provincias', 'index');
});

Route::controller(MunicipioController::class)->group(function () {
    Route::get('/municipios', 'index');
});

Route::controller(SectorController::class)->group(function () {
    Route::get('/sectores', 'index');
});


//FALL BACK ROUTE FOR NO URL FOUND
Route::fallback(function () {
    return response()->json([
        'status' => 'error', 'message' => 'Incorrect Route or not logged'
    ], ResponseCodes::NOT_FOUND);
});
