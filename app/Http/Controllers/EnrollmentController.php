<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/enrollments",
     *     summary="Enroll in a course",
     *     tags={"Enrollments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"course_id",},
     *             @OA\Property(property="course_id", type="integer", example="1"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="User enrolled successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    // Enroll in a course
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
            'data' => new EnrollmentResource($enrollment)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/enrollments",
     *     summary="Display enrollments",
     *     tags={"Enrollments"},
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Enrolled courses"),
     * )
     */

    // View enrollments
    public function myEnrollments(Request $request): JsonResponse
    {

        $enrollments = $request->user()->enrollments;

        return response()->json([
            'message' => 'Enrollments',
            'data' => EnrollmentResource::collection($enrollments)
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/enrollments/{id}",
     *     summary="Delete enrollment",
     *     tags={"Enrollments"},
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * ),
     *    security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Enrollment deleted successfully"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Enrollment not found")
     * )
     */

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
