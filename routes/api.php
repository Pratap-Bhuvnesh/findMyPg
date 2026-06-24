<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PGController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\PGInquiryController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\API\VisitorController;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/addstore', [PGController::class, 'store']);
     Route::get('/pgs/{id}', [PGController::class, 'show']);
    Route::put('/pgs/{id}', [PGController::class, 'update']);
    Route::delete('/pgs/{id}', [PGController::class, 'destroy']);
    Route::get('/mypglist', [PGController::class, 'mypglist']);

    Route::post('/pgs/{pg}/reviews', [ReviewController::class, 'store'])->middleware('prevent.owner.review');
    //Route::post('/pgs/reviews', [ReviewController::class, 'store'])->middleware('prevent.owner.review');

    Route::get('/pgs/{pg}/leads',[PGInquiryController::class, 'pgLeads']);
    Route::put('/inquiryleads/{id}/status', [PGInquiryController::class, 'updateStatus']); 

    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/agent/leads', [LeadController::class, 'myLeads']);
    Route::put('/leads/{id}/status', [LeadController::class, 'updateStatus']);
});
Route::post('/pg-inquiries', [PGInquiryController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/pgs', [PGController::class, 'index']);
Route::get('/universities', [UniversityController::class, 'index']);
Route::post('/activate-account', [AuthController::class, 'activateAccount']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/visitor-track', [VisitorController::class, 'track']);
Route::get('/visitor-count', [VisitorController::class, 'count']);
Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel.', function ($message) {
        $message->to('pratapp.singh4@gmail.com')
                ->subject('Laravel Test Email');
    });

    return 'Mail sent!';
});
Route::post('/email/resend', function (Request $request) {

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'message' => 'Email already verified'
        ]);
    }

    $user->sendEmailVerificationNotification();

    return response()->json([
        'message' => 'Verification email sent'
    ]);
});