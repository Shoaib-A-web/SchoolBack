<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TestUser;
use Illuminate\Support\Facades\Storage;

class TestUserController extends Controller
{
    // GET ALL USERS
    public function index()
    {
        $users = TestUser::latest()->get();

        if ($users->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No users found',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Users fetched successfully',
            'data' => $users
        ]);
    }

    // STORE USER
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'age'   => 'required|integer',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('uploads/test-users', 'public');
        }

        $user = TestUser::create([
            'name'  => $request->name,
            'age'   => $request->age,
            'phone' => $request->phone,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    // SHOW SINGLE USER
    public function show(string $id)
    {
        $user = TestUser::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'User fetched successfully',
            'data' => $user
        ]);
    }

    // UPDATE USER
    public function update(Request $request, string $id)
    {
        $user = TestUser::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'age'   => 'required|integer',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {

            if ($user->image) {
                Storage::disk('public')
                    ->delete($user->image);
            }

            $user->image = $request->file('image')
                ->store('uploads/test-users', 'public');
        }

        $user->name = $request->name;
        $user->age = $request->age;
        $user->phone = $request->phone;

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
        $user = TestUser::findOrFail($id);

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
