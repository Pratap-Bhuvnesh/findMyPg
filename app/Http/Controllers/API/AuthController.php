<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
                  return response()->json([
                //'message' => 'Please verify your email before logging in'
                 'message' => 'Invalid credentials',
                    'errors' => [
                        'unknown' => [
                            'Invalid credentials'
                        ]
                    ]
            ], 403);
                
        }
        // ✅ Email verification check
        if (is_null($user->email_verified_at)) {
            return response()->json([
                //'message' => 'Please verify your email before logging in'
                 'message' => 'Validation failed',
                    'errors' => [
                        'email' => [
                            'Please verify your email before log in'
                        ]
                    ]
            ], 403);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        $userdetail = User::select(['name','email','id','role','mobile'])->where('email', $request->email)->first();
        return response()->json([
            'token' => $token,
            'user' => $userdetail
        ]);
    }

    public function register(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:owner,student,agent',
        ]);

        if ($validator->fails()) {           
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
           
        // Create user
        $activationtoken = Str::random(64);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'activation_token' => $activationtoken,
        ]);

        // Generate token
        $verificationUrl = 'http://localhost:8080/email-verified?token=' . $activationtoken;
        $user->notify(new CustomVerifyEmail($verificationUrl));
        // Return response
        return response()->json([
            'message' => 'Kindly check your email.We have sent you activation link.',
        ], 201);
    }
    public function activateAccount(Request $request){ 
        $user = User::where('activation_token',$request->token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid activation link'
            ], 404);
        }

        $user->email_verified_at = now();
        $user->activation_token = null;
        $user->save();

        return response()->json([
            'message' => 'Account activated successfully'
        ]);
    }
    public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $token = Str::random(64);

    DB::table('password_resets')
        ->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

    $resetUrl = "http://localhost:8080/reset-password?token=".$token;

    $user->notify(new ForgotPasswordNotification($resetUrl));

    return response()->json([
        'message' => 'Password reset link sent to your email.'
    ]);
}
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'token' => 'required',
        'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $reset = DB::table('password_resets')
        ->where('token', $request->token)
        ->first();

    if (!$reset) {
        return response()->json([
            'message' => 'Invalid reset token'
        ], 404);
    }

    $user = User::where('email', $reset->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    DB::table('password_resets')
        ->where('email', $user->email)
        ->delete();

    return response()->json([
        'message' => 'Password reset successfully'
    ]);
}
}
