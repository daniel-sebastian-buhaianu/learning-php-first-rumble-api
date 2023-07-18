<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\VideoController;

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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/channel', [ChannelController::class, 'index']);
Route::post('/channel', [ChannelController::class, 'store']);
Route::get('/channel/{id}', [ChannelController::class, 'show']);
Route::put('/channel/{id}', [ChannelController::class, 'update']);
Route::delete('/channel/{id}', [ChannelController::class, 'destroy']);
Route::get('/channel/search/{title}', [ChannelController::class, 'search']);

Route::get('/video', [VideoController::class, 'index']);
Route::post('/video', [VideoController::class, 'store']);
// Route::get('/video/{id}', [VideoController::class, 'show']);
// Route::put('/video/{id}', [VideoController::class, 'update']);
// Route::delete('/video/{id}', [VideoController::class, 'destroy']);
// Route::get('/video/search/{title}', [VideoController::class, 'search']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth:sanctum'])->group(function () {});
