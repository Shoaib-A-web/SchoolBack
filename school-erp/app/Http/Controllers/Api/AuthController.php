<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class   AuthController extends Controller
{
    public function register(Request $req)
    {
        // Validation rules updated for the new payload structure
        $validator = Validator::make($req->all(), [
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'contact'          => 'nullable|string|regex:/^[0-9]{10}$/', // Validates a standard 10-digit number
            'password'         => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password', // Checks if it matches password
            'age'              => 'required|date|before:today', // Validates birthdate formatting
            'gender'           => 'nullable|string|in:Male,Female,Other',
            'aadhar'           => 'nullable|string|digits:12|unique:users,aadhar', // Exactly 12 numeric digits
            'schoolId'         => 'required|exists:schools,id', // Confirms school ID exists in DB
            'userType'         => 'required|in:Admin,Teacher,Student'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Database insertion mapping frontend payload to database schema columns
        $user = User::create([
            'first_name' => $req->first_name,
            'last_name'  => $req->last_name,
            'email'      => $req->email,
            'contact'    => $req->contact,
            'password'   => bcrypt($req->password), // Encrypts plain password
            'age'        => $req->age,
            'gender'     => $req->gender,
            'aadhar'     => $req->aadhar,
            'school_id'  => $req->schoolId, // Maps camelCase to snake_case DB column
            'userType'   => $req->userType, // Populates userType column
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Registered Successfully',
            'data' => $user
        ], 201);
    }

     /**
     * Authenticate user and issue API Token based on role.
     */
    public function login(Request $req)
    {
        // 1. Validate the incoming authentication payload
        $validator = Validator::make($req->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
            'userType' => 'required|string|in:Admin,Teacher,Student'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Locate the user by email
        $user = User::where('email', $req->email)->first();

        // 3. Verify user existence, password match, and specific userType clearance
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid email or password credentials'
            ], 401);
        }

        if ($user->userType !== $req->userType) {
            return response()->json([
                'status'  => false,
                'message' => 'Access denied. Incorrect user type selection.'
            ], 403);
        }

        // 4. Generate a plain-text access token using Laravel Sanctum
        $token = $user->createToken($user->email . '_Token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'User logged in successfully',
            'token'   => $token,
            'data'    => $user
        ], 200);
    }

    /**
     * Revoke the user's current access token (Logout).
     */
    public function logout(Request $req)
    {
        // 1. Grab the currently authenticated user and delete their active token
        $req->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User logged out successfully'
        ], 200);
    }


}
