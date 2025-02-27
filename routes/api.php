<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Feeses\FeesesController;
use App\Http\Controllers\SpecializionsController;
use App\Http\Controllers\Images\UserDocsImagesController;
use App\Http\Controllers\Appointments\AppointmentsController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Reservations\ReservationsController;
use App\Http\Controllers\Documents\UserDocumentationController;
use App\Http\Controllers\Reviews\ReviewsController;
use App\Http\Controllers\Specialization\SpecializationController;
use App\Models\Contact;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::prefix('users')->group(function () {

    Route::prefix('get-doctors')->group(function () {
        Route::get('/', [UsersController::class, 'getDoctors']);
        Route::get('/best-specialization-doctor', [UsersController::class, 'getTopDoctorsBySpecialization']);
        Route::get('/show/{id}', [UsersController::class, 'showDoctor']);
        Route::get('/reviews-client', [UsersController::class, 'ourHappyClient']);
    });

    Route::post('/check-forget-password', [VerifyController::class, 'verifyForgetPassword']);
    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/send-code', [VerifyController::class, 'sendCode']);
        Route::post('/check-code', [VerifyController::class, 'checkCode']);
        Route::post('/contact', [ContactController::class, 'store']);
        Route::get('/documentation', [UsersController::class, 'getDocumentations']);
    });
});

Route::prefix('/')->middleware('auth:sanctum')->group(function () {

    Route::prefix('specialization')->middleware('check.Get_Doctor_User')->group(function () {
        Route::get('/', [SpecializationController::class, 'index']);
        Route::post('/store', [SpecializationController::class, 'store']);
        Route::delete('/{id}', [SpecializationController::class, 'delete']);
    });
    Route::prefix('feeses')->middleware('check.Get_Admin_Doctor')->group(function () {
        Route::get('/', [FeesesController::class, 'index']);
        Route::post('/store', [FeesesController::class, 'store']);
        Route::post('/{id}', [FeesesController::class, 'update']);
    });
    Route::prefix('user_documentations')->group(function () {
        Route::get('/', [UserDocumentationController::class, 'index']);
        Route::post('/store', [UserDocumentationController::class, 'store'])->middleware('check.Get_Doctor_User');
        Route::post('/update/{id}', [UserDocumentationController::class, 'update']);
        Route::delete('/delete/{id}', [UserDocumentationController::class, 'delete']);
    });
    Route::prefix('user_docs_images')->group(function () {
        Route::get('/', [UserDocsImagesController::class, 'index']);
        Route::post('/store', [UserDocsImagesController::class, 'store'])->middleware('check.Get_Doctor_User');
        Route::post('/update/{id}', [UserDocsImagesController::class, 'update']);
        Route::delete('/delete/{id}', [UserDocsImagesController::class, 'delete']);
    });
    Route::prefix('reservations')->middleware('check.Get_Doctor_User')->group(function () {
        Route::get('/', [ReservationsController::class, 'index']);
        Route::post('/store', [ReservationsController::class, 'store']);
        Route::post('/cancel/{id}', [ReservationsController::class, 'cancel']);
    });
    Route::prefix('reviews')->middleware('check.Get_user')->group(function () {
        Route::post('/store', [ReviewsController::class, 'store']);
        Route::post('/update/{id}', [ReviewsController::class, 'update']);
        Route::delete('/delete/{id}', [ReviewsController::class, 'delete']);
    });
});
// DOCTORS
Route::prefix('doctors')->middleware('auth:sanctum', 'check.Get_doctor')->group(function () {

    Route::prefix('appointments')->group(function () {
        Route::get('/all-appointements', [DoctorController::class, 'allAppointements']);
        Route::post('/store', [AppointmentsController::class, 'store']);
        Route::post('/update/{id}', [AppointmentsController::class, 'update']);
        Route::delete('/delete/{id}', [AppointmentsController::class, 'delete']);
    });
    
    Route::prefix('reservations')->group(function () {
        Route::get('/', [DoctorController::class, 'getAllReservations']);
        Route::get('/today-reservations', [DoctorController::class, 'todayReservations']);
        Route::get('/complete_reservations/{id}', [DoctorController::class, 'completeReservations']);
    });

    Route::prefix('users')->group(function() {
        Route::get('/', [DoctorController::class, 'getUsers']);
        Route::get('/{id}', [DoctorController::class, 'getUser']);
    });

    Route::prefix('documentations')->group(function () {
        Route::post('/store', [DoctorController::class, 'storeDocs']);
        Route::post('/update/{id}', [DoctorController::class, 'updateDocs']);
        Route::delete('/delete/{id}', [DoctorController::class, 'deleteDocs']);
    });

    Route::prefix('reviews')->group(function () {
        Route::get('/', [DoctorController::class, 'getAllReviews']);
    });
});
// Admin 
Route::prefix('admin')->middleware(['auth:sanctum', 'check.Get_Admin'])->group(function() {

    Route::prefix('/')->group(function() {
        Route::get('/make/{id}', [AdminController::class, 'makeAdmin']);
        Route::get('/dashboard', [AdminController::class, 'allContentInDashboard']);
    });

    Route::prefix('users')->group(function() {
        Route::get('/', [AdminController::class, 'getUsers']);
        Route::get('/{id}', [AdminController::class, 'getUser']);
        Route::delete('/{id}', [AdminController::class, 'deleteUser']);
    });

    Route::prefix('doctors')->group(function() {
        Route::get('/', [AdminController::class, 'getDoctors']);
        Route::get('/{id}', [AdminController::class, 'getDoctor']);
        Route::get('/make/{id}', [AdminController::class, 'makeDoctor']);
    });

    Route::prefix('reviews')->group(function() {
        Route::delete('/{id}', [AdminController::class, 'deleteReview']);
    });

});

// Route""