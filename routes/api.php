<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\authController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('login', [authController::class, 'login']);
    Route::post('register', [authController::class, 'register']);
});

Route::prefix('courses')->middleware('auth:user-api')->group(function () {
    Route::get('', [CourseController::class, 'index']);
    Route::post('store', [CourseController::class, 'store']);
    Route::get('show/{id}', [CourseController::class, 'show']);
    Route::post('update/{id}', [CourseController::class, 'update']);
    Route::delete('delete/{id}', [CourseController::class, 'destroy']);
});

Route::prefix('user')->middleware('auth:user-api')->group(function () {
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('update', [UserController::class, 'update']);
    Route::get('courses', [UserController::class, 'courses']);
});

Route::prefix('question')->middleware('auth:user-api')->group(function () {
    Route::get('{id}', [QuestionController::class, 'index']);
    Route::post('store', [QuestionController::class, 'store']);
    Route::put('update/{id}', [QuestionController::class, 'update']);
    Route::delete('delete/{id}', [QuestionController::class, 'destroy']);
});

Route::prefix('answer')->middleware('auth:user-api')->group(function () {
    Route::get('', [AnswerController::class, 'index']);
    Route::post('store', [AnswerController::class, 'store']);
});
