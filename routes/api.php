<?php

use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\PersonCountController;
use App\Http\Controllers\Api\PersonSearchController;
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

Route::post('/pessoas', [PersonController::class, 'store']);
Route::get('/pessoas/{uuid}', [PersonController::class, 'show']);
Route::get('/pessoas', PersonSearchController::class);
Route::get('/contagem-pessoas', PersonCountController::class);
