<?php

namespace App\Http\Controllers;

use App\Models\TempStd;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TempStdController extends Controller
{   
    // count of students
    public function count(): JsonResponse
    {
        $studentCount = TempStd::count();
        return response()->json([
            'status' => true,
            'message' => 'Student count retrieved successfully',
            'data' => $studentCount
        ], 200);
    }
    /**
     * Get All Students
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => TempStd::latest()->get()
        ], 200);
    }

    /**
     * Store Student
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $data = $request->except([
                'fsameas',
                'msameas'
            ]);

            $student = TempStd::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Student created successfully',
                'data' => $student
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show Single Student
     */
    public function show($id): JsonResponse
    {
        $student = TempStd::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $student
        ], 200);
    }

    /**
     * Update Student
     */
    public function update(Request $request, $id): JsonResponse
    {
        $student = TempStd::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $data = $request->except([
            'fsameas',
            'msameas'
        ]);

        $student->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Student updated successfully',
            'data' => $student
        ], 200);
    }

    /**
     * Delete Student
     */
    public function destroy($id): JsonResponse
    {
        $student = TempStd::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully'
        ], 200);
    }
}