<?php

namespace App\Http\Controllers\User;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    public function getUserCount()
    {  
        $userCount = User::count();

        return response()->json([
            'status' => true,
            'message' => 'User count retrieved successfully',
            'data' => $userCount
        ], 200);
    }

    public function getUser()
    {
         $users = User::select([
            'id', 
            'first_name', 
            'last_name', 
            'email', 
            'contact', 
            'age', 
            'gender', 
            'aadhar', 
            'school_id', 
            'image',
            'userType', 
            'created_at'
        ])->get();

        return response()->json([
            'status' => true,
            'message' => 'User List Retrieved Successfully',
            'data' => $users
        ], 200);
    }
    
    public function getUser_id(string $id)
    { 
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'User Retrieved Successfully',
            'data' => $user
        ], 200);
    }


// public function update(Request $request,string $id)
// {
//     // 1. Find user in the correct table
//     $user = User::find($id);

//     if (!$user) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found'
//         ], 404);
//     }

//     // 2. Validate payload dynamically (sometimes = optional but must match rules if present)
//     $validator = Validator::make($request->all(), [
//         'first_name' => 'sometimes|string|max:255',
//         'last_name'  => 'sometimes|string|max:255',
//         'email'      => 'sometimes|email|unique:users,email,' . $id,
//         'gender'     => 'sometimes|string|in:Male,Female,Other',
//         'userType'   => 'sometimes|string',
//         'contact'    => 'sometimes|digits:10|unique:users,contact,' . $id,
//         'aadhar'     => 'sometimes|digits:12|unique:users,aadhar,' . $id,
//         'age'        => 'sometimes|date',
//         'image'      => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     // 3. Extract only the validated data sent in the request
//     $data = $validator->validated();

//     // 4. Handle binary image update safely
//     if ($request->hasFile('image')) {
//         // Delete old image using Laravel Storage system if it exists
//         if ($user->image) {
//             Storage::disk('public')->delete($user->image);
//         }
//         // Store new image file path
//         $data['image'] = $request->file('image')->store('users', 'public');
//     }

//     // 5. Update only the provided fields safely
//     $user->update($data);

//     return response()->json([
//         'status' => true,
//         'message' => 'Profile updated successfully',
//         'data'    => $user
//     ], 200);
// }

// UPDATE USER
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'nullable|string|min:2|max:255',
            'last_name'  => 'nullable|string|min:2|max:255',

            'email' => 'nullable|email|max:255|unique:users,email,' . $id,

            'contact' => 'nullable|digits:10|unique:users,contact,' . $id,

            'aadhar' => 'nullable|digits:12|unique:users,aadhar,' . $id,

            'gender' => 'nullable|in:Male,Female,Other',

            'userType' => 'nullable|string|max:50',

            'age' => 'nullable|date|before:today',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // if ($request->hasFile('image')) {

        //     if ($user->image) {
        //         Storage::disk('public')
        //             ->delete($user->image);
        //     }

        //     $user->image = $request->file('image')
        //         ->store('uploads/user/profilepics', 'public');
        // }

    if ($request->hasFile('image')) {

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->image = $request
            ->file('image')
            ->store('uploads/user/profilepics', 'public');
    }

        

        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name  = $request->last_name ?? $user->last_name;
        $user->email      = $request->email ?? $user->email;
        $user->contact    = $request->contact ?? $user->contact;
        $user->aadhar     = $request->aadhar ?? $user->aadhar;
        $user->gender     = $request->gender ?? $user->gender;
        $user->userType   = $request->userType ?? $user->userType;
        $user->age        = $request->age ?? $user->age;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    // DELETE USER
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->image) {
            Storage::disk('public')
                ->delete($user->image);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
