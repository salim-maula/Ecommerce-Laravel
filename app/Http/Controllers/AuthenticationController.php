<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResponseFormatter;
use App\Models\User;
use Validator; // Add the Validator facade
use Mail; // Add the Mail facade

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        // Return validation error if fails
        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()); // Return validation errors, not a boolean
        }

        // Generate a unique OTP
        do {
            $otp = rand(100000, 999999);
            $otpCount = User::where('otp_register', $otp)->count();
        } while ($otpCount > 0); // Ensure no duplicate OTP

        // Create a new user
        $user = User::create([
            'email' => $request->email,
            'name' => $request->email,
            'otp_register' => $otp
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new \App\Mail\SendRegisterOTP($user));

        // Return success response
        return ResponseFormatter::success([
            'is_sent' => true
        ]);
    }

    public function verifyOtp()
    {
        // Implement OTP verification logic
    }

    public function verifyRegister()
    {
        // Implement registration verification logic
    }
}
