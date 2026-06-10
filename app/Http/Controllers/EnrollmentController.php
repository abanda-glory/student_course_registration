<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollRequest;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(EnrollRequest $request): JsonResponse
    {

        // Check if user is enrolled already

        $enrolled = Enrollment::where('user_id', $request->user()->id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($enrolled) {
            return response()->json([
                'message' => 'Already enrolled',
            ], 422);
        }

        $enrollment = Enrollment::create([
            'user_id' => $request->user()->id,
            'course_id' => $request->course_id,
            'enrollment_date' => now()->toDateString()
        ]);

        return response()->json([
            'message' => 'Successfully Enrolled',
            'data' => $enrollment
        ], 201);
    }

    public function myEnrollments(Request $request): JsonResponse
    {

        $enrollments = $request->user()->enrollments;

        return response()->json([
            'message' => 'Enrollments',
            'data' => $enrollments
        ], 200);
    }

    public function cancel(Request $request, $id): JsonResponse
    {
        $enrollment = Enrollment::findOrFail($id);

        if ($enrollment->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $enrollment->delete();

        return response()->json([
            'message' => 'Enrollment successfully deleted'
        ], 200);
    }
}
