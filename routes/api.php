<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix'=>'auth',
    'as'=>'auth.'
], function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::get('/me', [\App\Http\Controllers\AuthController::class, 'me'])->middleware('auth:api')->name('me');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('/users', \App\Http\Controllers\UserController::class)->names('users');
    Route::apiResource('/roles', \App\Http\Controllers\RoleController::class)->names('roles');
    Route::apiResource('/permissions', \App\Http\Controllers\PermissionController::class)->names('permissions');
});
