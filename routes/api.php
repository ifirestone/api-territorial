<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Tools\ResponseCodes;

# Territoriales
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\DistritoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//TERRITORIAL
Route::get('/paises', [CountryController::class, 'index']);
Route::get('/paises/{pais}/show', [CountryController::class, 'show']);
Route::get('/provincias', [ProvinciaController::class, 'index']);
Route::get('/provincias/{provincia}/show', [ProvinciaController::class, 'show']);
Route::get('/municipios', [MunicipioController::class, 'index']);
Route::get('/municipios/{municipio}/show', [MunicipioController::class, 'show']);
Route::get('/distritos', [DistritoController::class, 'index']);
Route::get('/distritos/{distrito}/show', [DistritoController::class, 'show']);

Route::fallback(function () {
    return response()->json([
        'status' => 'error', 'message' => 'Ruta incorrecta o usuario no autenticado'], ResponseCodes::NOT_FOUND);
});