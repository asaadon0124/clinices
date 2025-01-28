<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Feeses\FeesesController;
use App\Http\Controllers\SpecializionsController;
use App\Http\Controllers\Images\UserDocsImagesController;
use App\Http\Controllers\Appointments\AppointmentsController;
use App\Http\Controllers\Documents\UserDocumentationController;
use App\Http\Controllers\Specialization\SpecializationController;

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
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/send-code', [VerifyController::class, 'sendCode']);
        Route::post('/check-code', [VerifyController::class, 'checkCode']);
    });

});

Route::prefix('/')->middleware('auth:sanctum')->group(function(){

    Route::prefix('specialization')->middleware('check.Get_Doctor_User')->group(function()
    {
        Route::get('/', [SpecializationController::class, 'index']);
        Route::post('/store', [SpecializationController::class, 'store']);
        Route::delete('/{id}', [SpecializationController::class, 'delete']);
    });
    
    Route::prefix('feeses')->middleware('check.Get_Admin_Doctor')->group(function()
    {
        Route::get('/', [FeesesController::class, 'index']);
        Route::post('/store', [FeesesController::class, 'store']);
        Route::post('/{id}', [FeesesController::class, 'update']);
    });

    Route::prefix('appointments')->middleware('check.Get_User_Admin')->group(function()
    {
        Route::get('/', [AppointmentsController::class, 'index']);
        Route::post('/store', [AppointmentsController::class, 'store']);
        Route::post('/update/{id}', [AppointmentsController::class, 'update']);
        Route::delete('/delete/{id}', [AppointmentsController::class, 'delete']);
    });


    Route::prefix('user_documentations')->group(function()
    {
        Route::get('/', [UserDocumentationController::class, 'index']);
        Route::post('/store', [UserDocumentationController::class, 'store'])->middleware('check.Get_Doctor_User');
        Route::post('/update/{id}', [UserDocumentationController::class, 'update']);
        Route::delete('/delete/{id}', [UserDocumentationController::class, 'delete']);
    });


    Route::prefix('user_docs_images')->group(function()
    {
        Route::get('/', [UserDocsImagesController::class, 'index']);
        Route::post('/store', [UserDocsImagesController::class, 'store'])->middleware('check.Get_Doctor_User');
        Route::post('/update/{id}', [UserDocsImagesController::class, 'update']);
        Route::delete('/delete/{id}', [UserDocsImagesController::class, 'delete']);
    });

});
