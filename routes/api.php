<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(StudentController::class)->group(function () {
    Route::get('/students', 'index');
    Route::get('/student/{id}', 'show');
    //Route::post('/orders', 'store');
});