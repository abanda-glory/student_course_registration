<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::all();

        return response()->json([
            'message' => 'Available Courses',
            'data' => $courses
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $course = Course::findOrFail($id);

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => $course
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:courses,title|max:255',
            'code' => 'required|string|unique:courses,code|max:100',
            'description' => 'required|string',
            'credit_hours' => 'required|integer|min:1'
        ]);

        // Create course into database
        $course = Course::create($validated);

        return response()->json([
            'message' => 'Course successfully Created',
            'data' => $course
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|unique:courses,title,' . $id . '|max:255',
            'code' => 'sometimes|required|string|unique:courses,code,' . $id . '|max:100',
            'description' => 'sometimes|required|string',
            'credit_hours' => 'sometimes|required|integer|min:1'
        ]);

        $course->update($validated);

        return response()->json([
            'message' => 'Update Successful!'
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully'
        ], 200);
    }
}
