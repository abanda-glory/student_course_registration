<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
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

    public function store(StoreCourseRequest $request): JsonResponse
    {

        // Create course into database
        $course = Course::create($request->validated());

        return response()->json([
            'message' => 'Course successfully Created',
            'data' => $course
        ], 201);
    }

    public function update(UpdateCourseRequest $request, $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $course->update($request->validated());

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
