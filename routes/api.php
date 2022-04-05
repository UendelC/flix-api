<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/videos/free', [VideoController::class, 'freeVideos']);

Route::middleware('auth:api')->group(
    function () {
        Route::apiResource('/videos', VideoController::class);
        Route::get(
            'categories/{category}/videos',
            [CategoryController::class, 'videos']
        )->name('categories.videos');
        Route::apiResource('/categories', CategoryController::class);
    }
);
