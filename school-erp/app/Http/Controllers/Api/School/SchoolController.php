<?php

namespace App\Http\Controllers\Api\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\School;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    //

    /**
     * Get a lightweight list of schools for frontend dropdowns.
     */
    public function getSchoolList(): JsonResponse
    {
        // Fetch only id and schoolName columns to keep the response fast and lightweight
        $schools = School::select('id', 'schoolName')->get();
        if ($schools->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No schools found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Schools retrieved successfully',
            'data'    => $schools
        ], 200);
    }

    public function getSchoolNameById(string $id): JsonResponse
    {
        $school = School::select('schoolName')->find($id);
        if (!$school) {
            return response()->json([
                'status' => false,
                'message' => 'School not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'School retrieved successfully',
            'data' => $school
        ], 200);
    }

    public function getSchoolById(string $id): JsonResponse
    {// echo "getSchoolById called with id: $id"; // Debugging line
        $school = School::find($id);
        if (!$school) {
            return response()->json([
                'status' => false,
                'message' => 'School not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'School retrieved successfully',
            'data' => $school
        ], 200);
    }

    public function getAllSchools(): JsonResponse
    {
        $schools = School::all();
        if ($schools->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No schools found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Schools retrieved successfully',
            'data' => $schools
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'board'            => 'required|string|max:100',
            'schoolName'       => 'required|string|max:255',
            'directorName'     => 'required|string|max:255',
            'schoolContact'    => 'required|string|max:20',
            'email'            => 'required|email|unique:schools,email',
            'website'          => 'nullable|string|max:255',
            'schoolAdd'        => 'required|string',
            'established_year' => 'required|digits:4',
            'code'             => 'required|string|unique:schools,code',
            'city'             => 'required|string|max:100',
            'pincode'          => 'required|string|max:10',
            'schoolLogo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
            ], 422);
        }

        $data = $request->all();

        // Upload Logo
        if ($request->hasFile('schoolLogo')) {
            $data['schoolLogo'] = $request
                ->file('schoolLogo')
                ->store('logos', 'public');
        }

        $school = School::create($data);

        return response()->json([
            'status' => true,
            'message' => 'School created successfully',
            'data' => $school
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json([
                'status' => false,
                'message' => 'School not found'
            ], 404);
        }

        $validated = $request->validate([
            'board'            => 'required|string|max:100',
            'schoolName'       => 'required|string|max:255',
            'directorName'     => 'required|string|max:255',
            'schoolContact'    => 'required|string|max:20',
            'email'            => 'required|email|unique:schools,email,' . $id,
            'website'          => 'nullable|string|max:255',
            'schoolAdd'        => 'required|string',
            'established_year' => 'required|digits:4',
            'code'             => 'required|string|unique:schools,code,' . $id,
            'city'             => 'required|string|max:100',
            'pincode'          => 'required|string|max:10',
            'schoolLogo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
            ], 422);
        }

        $data = $request->all();

        // Update Logo
        if ($request->hasFile('schoolLogo')) {

            // Delete old logo
            if ($school->schoolLogo) {
                Storage::disk('public')->delete($school->schoolLogo);
            }

            $data['schoolLogo'] = $request
                ->file('schoolLogo')
                ->store('logos', 'public');
        }
        $school->schoolName = $request->schoolName;
        $school->board = $request->board;
        $school->directorName = $request->directorName;
        $school->schoolContact = $request->schoolContact;
        $school->email = $request->email;
        $school->website = $request->website;
        $school->schoolAdd = $request->schoolAdd;
        $school->established_year = $request->established_year;
        $school->code = $request->code;
        $school->city = $request->city;
        $school->pincode = $request->pincode;

        $school->save();

        return response()->json([
            'status' => true,
            'message' => 'School updated successfully',
            'data' => $school
        ]);
    }

    public function show(string $id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json([
                'status' => false,
                'message' => 'School not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $school
        ]);
    }

    public function destroy(string $id)
    {
        $school = School::find($id);

        if (!$school) {
            return response()->json([
                'status' => false,
                'message' => 'School not found'
            ], 404);
        }

        // Delete logo file
        if ($school->schoolLogo) {
            Storage::disk('public')->delete($school->schoolLogo);
        }

        $school->delete();

        return response()->json([
            'status' => true,
            'message' => 'School deleted successfully'
        ]);
    }
}
