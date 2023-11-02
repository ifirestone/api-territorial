<?php

use App\Tools\ResponseCodes;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (!env('APP_DEBUG')) {
        return response()->json(['status' => 'error', 'message' => 'No autorizado'], ResponseCodes::UNAUTHORIZED);
    } else {
        return redirect('api/documentation');
    }
});

Route::fallback(function () {
    return response()->json(['status' => 'error', 'message' => 'Incorrect Route'], ResponseCodes::NOT_FOUND);
});
