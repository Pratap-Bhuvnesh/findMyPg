<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PGController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/pgs', [PGController::class, 'store']);
    Route::post('/reviews', [ReviewController::class, 'store'])->middleware('prevent.owner.review');
});

Route::post('/login', [AuthController::class, 'login']);
Route::get('/pgs', [PGController::class, 'index']);