<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\MarkController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(StudentController::class)->group(function () {
    Route::get('/students', 'index');
    Route::get('/student/{student}', 'show');
    Route::post('/student', 'store');
    Route::put('/student/{student}', 'update');
    Route::delete('/student/{student}', 'destroy');
});

Route::controller(SubjectController::class)->group(function () {
    Route::get('/subjects', 'index');
    Route::get('/subject/{subject}', 'show');
    Route::post('/subject', 'store');
    Route::put('/subject/{subject}', 'update');
    Route::delete('/subject/{subject}', 'destroy');
});

Route::controller(MarkController::class)->group(function () {
    Route::get('marks', 'index');
    Route::get('mark/{mark}', 'show');
    Route::post('mark','store');
    Route::get('marksAvgByStudent/{student}',  'getAvgMarksByStudent');
    Route::get('marksByStudent/{student}', 'getMarksByStudent');
    Route::get('allMarksAvg', 'getAllAvgMarks');
    Route::get('marksAvgBySubject/{subject}', 'getAvgMarksBySubject');
    Route::put('mark/{mark}', 'update');
    Route::delete('mark/{mark}', 'destroy');
});
