<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Feeses\FeesesController;
use App\Http\Controllers\Specialization\SpecializationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) 
{
    return $request->user(); 
});


Route::prefix('users')->group(function()
{
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/check-forget-password', [VerifyController::class, 'verifyForgetPassword']);
    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/send-code', [VerifyController::class, 'sendCode']);
        Route::post('/check-code', [VerifyController::class, 'checkCode']);
    });

});

Route::prefix('/')->middleware('auth:sanctum')->group(function(){

    Route::prefix('specialization')->middleware('check.role')->group(function(){
        Route::get('/', [SpecializationController::class, 'index']);
        Route::post('/store', [SpecializationController::class, 'store']);
        Route::get('/{id}', [SpecializationController::class, 'delete']);
    });
    
    Route::prefix('feeses')->middleware('check.feeses')->group(function(){
        Route::get('/', [FeesesController::class, 'index']);
        Route::post('/store', [FeesesController::class, 'store']);
        Route::post('/{id}', [FeesesController::class, 'update']);
    });

});
