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

Route::post('register', [\App\Http\Controllers\API\RegisterController::class, 'register']);
Route::post('login', [\App\Http\Controllers\API\RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    ## Logout
    Route::get('logout', [\App\Http\Controllers\API\RegisterController::class, 'logout']);

    ## Periods
    Route::resource('periods', \App\Http\Controllers\API\PeriodController::class);
    Route::get('periods/{id}/students', [\App\Http\Controllers\API\PeriodController::class, 'students']);

    ## Teachers
    Route::resource('teachers', \App\Http\Controllers\API\TeacherController::class);
    Route::get('teachers/{id}/periods', [\App\Http\Controllers\API\TeacherController::class, 'periods']);
    Route::get('teachers/{id}/students', [\App\Http\Controllers\API\TeacherController::class, 'students']);

    ## Students
    Route::resource('students', \App\Http\Controllers\API\StudentController::class);
    Route::delete('students/{id}/period', [\App\Http\Controllers\API\StudentController::class, 'deletePeriod']);
    Route::put('students/{id}/period', [\App\Http\Controllers\API\StudentController::class, 'updateOrCreatePeriod']);
});
