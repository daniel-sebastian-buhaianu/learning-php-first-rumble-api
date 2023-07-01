<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Channel
    Route::controller(ChannelController::class)->group(function () {
        Route::get('channel', 'index');
        Route::post('channel', 'store');
        Route::get('channel/{id}', 'show');
        Route::put('channel/{id}', 'update');
        Route::delete('channel/{id}', 'destroy');
        Route::get('channel/search/{title}', 'search');
    });

    // // Video Category
    // Route::controller(VideoCategoryController::class)->group(function () {
    //     Route::get('/video-category', 'index');
    //     Route::post('/video-category', 'store')->middleware('can:create,App\Models\VideoCategory');
    //     Route::get('/video-category/{id}', 'show');
    //     Route::put('/video-category/{id}', 'update')->middleware('can:update,App\Models\VideoCategory');
    //     Route::delete('/video-category/{id}', 'destroy')->middleware('can:delete,App\Models\VideoCategory');
    //     Route::get('/video-category/search/{name}', 'search');
    // });

    // // Rumble Video
    // Route::controller(RumbleVideoController::class)->group(function () {
    //  Route::get('/rumble-video', 'index');
    //  Route::post('/rumble-video', 'store')->middleware('can:create,App\Models\RumbleVideo');
    //  Route::get('/rumble-video/{id}', 'show');
    //  Route::put('/rumble-video/{id}', 'update')->middleware('can:update,App\Models\RumbleVideo');
    //  Route::delete('/rumble-video/{id}', 'destroy')->middleware('can:delete,App\Models\RumbleVideo');
    //  Route::get('/rumble-video/search/{name}', 'search');
});