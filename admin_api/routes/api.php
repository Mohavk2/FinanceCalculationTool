<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Defaults\TemplateController;
use App\Http\Controllers\Api\v1\Defaults\CategoryController;
use App\Http\Controllers\Api\v1\Defaults\OperationController;
use \App\Http\Controllers\Api\AuthenticationController;


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

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::resource('/users', UserController::class)->name('index', 'users');
    Route::group([
        'prefix' => 'defaults'
    ], function (){
        Route::apiResource('/operations', OperationController::class)->name('index', 'operations');
        Route::apiResource('/categories', CategoryController::class)->name('index', 'categories');
        Route::apiResource('/templates', TemplateController::class)->name('index', 'templates');
    });
});

