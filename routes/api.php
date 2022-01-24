<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>['api','checkPassword','checkAdminToken:admin-api']], function () {

    Route::post('post', [\App\Http\Controllers\Post::class, 'allPost']);
    Route::post('post/create', [\App\Http\Controllers\Post::class, 'create']);
    Route::put('post/update', [\App\Http\Controllers\Post::class, 'update']);
    Route::delete('post/delete', [\App\Http\Controllers\Post::class, 'delete']);
    Route::post('post/{id}', [\App\Http\Controllers\Post::class, 'getById']);
});

//----------------------------------------------------------------------------------------------------------
//Authenticat Route whit JWT

Route::group([
    'middleware' => ['checkPassword'],
    'prefix' => 'admin'

], function () {

    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('checkAdminToken:admin-api','checkGuard:admin-api');
    Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\AuthController::class, 'me']);
});



Route::group(['prefix' => 'user', 'middleware' => ['checkPassword']], function () {

    Route::post('login', [\App\Http\Controllers\AuthController::class, 'userLogin']);
    Route::post('profile', function () {
        return \Illuminate\Support\Facades\Auth::user();
    })->middleware('checkGuard:user-api');
});


