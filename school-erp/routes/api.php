<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TestUserController;
use App\Http\Controllers\TempStdController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Api\School\SchoolController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);  //register route for new users
Route::post('/userType/login', [AuthController::class, 'login']);  //login route for users to authenticate and receive a token
Route::post('/logout', [AuthController::class, 'logout']);  //logout route for users to invalidate their token

Route::get('/users', [UserController::class, 'getUser']); // Fetch user types for dropdowns or role management
Route::get('/user_count', [UserController::class, 'getUserCount']); // Fetch total user count for dashboard statistics
Route::get('/users/{id}', [UserController::class, 'getUser_id']); // Fetch specific user details by ID
Route::post('/users/{id}', [UserController::class, 'update']); // Update specific user details by ID
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete specific user by ID
Route::apiResource('test-users',TestUserController::class);



Route::get('/schoolNameId', [SchoolController::class, 'getSchoolList']);
Route::get('/schools/{id}', [SchoolController::class, 'getSchoolNameById']);
Route::get('/school/{id}', [SchoolController::class, 'getSchoolById']);
Route::get('/schools', [SchoolController::class, 'getAllSchools']);
Route::post('/schools', [SchoolController::class, 'store']);
Route::post('/school_update/{id}', [SchoolController::class, 'update']);
Route::delete('/school/{id}', [SchoolController::class, 'destroy']);

Route::apiResource('temp-stds', TempStdController::class);   // Temporary route for handling student data during the admission process, allowing for creation, retrieval, updating, and deletion of temporary student records before finalizing their admission into the system.
Route::get('/temp_stds_count', [TempStdController::class, 'count']); // Route to get the count of temporary student records, useful for dashboard statistics or monitoring the number of students in the admission process.

// Protected Routes (Requires a valid Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
});

// Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
//     Route::apiResource('students', StudentController::class);
// });
