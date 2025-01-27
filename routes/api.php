<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\SpecializionsController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('users')->middleware('auth:sanctum')->group(function()
{
    
    Route::post('/check-forget-password', [VerifyController::class, 'verifyForgetPassword']);
    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);


    Route::get('/test', [AuthController::class, 'test']);
    Route::get('/all_tests', [testController::class, 'index']);
    Route::post('/store_test', [testController::class, 'store_test']);
    Route::get('/get_test/{id}', [testController::class, 'get_test']);

    Route::prefix('specializions')->group(function()
    {
        Route::get('/', [SpecializionsController::class, 'index']);
        Route::get('/create', [SpecializionsController::class, 'create']);
        Route::post('/store', [SpecializionsController::class, 'store']);
        Route::get('/edit/{id}', [SpecializionsController::class, 'edit']);
        Route::put('/update/{id}', [SpecializionsController::class, 'update']);
        Route::post('/delete/{id}', [SpecializionsController::class, 'delete']);
    });


    Route::middleware('auth:sanctum')->group(function()
    {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/send-code', [VerifyController::class, 'sendCode']);
        Route::post('/check-code', [VerifyController::class, 'checkCode']);
    });
});