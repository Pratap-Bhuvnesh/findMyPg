<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PGController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\PGInquiryController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/addstore', [PGController::class, 'store']);
     Route::get('/pgs/{id}', [PGController::class, 'show']);

    Route::put('/pgs/{id}', [PGController::class, 'update']);

    Route::delete('/pgs/{id}', [PGController::class, 'destroy']);

    Route::post('/pg-inquiries', [PGInquiryController::class, 'store']);
    Route::get('/pgs/{pg}/leads',[PGInquiryController::class, 'pgLeads']);
    Route::get('/mypglist', [PGController::class, 'mypglist']);
    Route::post('/pgs/{pg}/reviews', [ReviewController::class, 'store'])->middleware('prevent.owner.review');
    //Route::post('/pgs/reviews', [ReviewController::class, 'store'])->middleware('prevent.owner.review');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/pgs', [PGController::class, 'index']);