<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/courses",
     *     summary="Displays a list of courses",
     *     tags={"Courses"},
     *     @OA\Response(response=200, description="Available courses"),
     * )
     */

    // Display courses
    public function index(Request $request): JsonResponse
    {
        // Access query string value found in the client's request url
        $search = $request->query('search');

        // Query courses
        $query = Course::query();

        if ($search) {
            $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'LIKE', '%' . $search . '%');
        }

        // Display courses on 10 pages
        $courses = $query->paginate(10);

        return response()->json([
            'message' => 'Available Courses',
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/courses/{id}",
     *     summary="Display course by id",
     *     tags={"Courses"},
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * ),
     *     @OA\Response(response=200, description="Course retrieved successfully"),
     * )
     */

    // Display course by id
    public function show($id): JsonResponse
    {
        $course = Course::findOrFail($id);

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/courses",
     *     summary="Register a new course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","code","description","credit_hours"},
     *             @OA\Property(property="title", type="string", example="Web Development"),
     *             @OA\Property(property="code", type="string", example="WEB101"),
     *             @OA\Property(property="description", type="string", example="This is an introduction to web development"),
     *             @OA\Property(property="credit_hours", type="integer", example="2")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Course created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    // Create a new course
    public function store(StoreCourseRequest $request): JsonResponse
    {

        // Create course into database
        $course = Course::create($request->validated());

        return response()->json([
            'message' => 'Course successfully Created',
            'data' => new CourseResource($course)
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/courses/{id}",
     *     summary="Update a course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Web Development"),
     *             @OA\Property(property="code", type="string", example="WEB101"),
     *             @OA\Property(property="description", type="string", example="This is an introduction to web development"),
     *             @OA\Property(property="credit_hours", type="integer", example="2")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Course updated successfully"),
     *     @OA\Response(response=404, description="Course not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */

    // Update course by id
    public function update(UpdateCourseRequest $request, $id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $course->update($request->validated());

        return response()->json([
            'message' => 'Update Successful!',
            'data' => new CourseResource($course)
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/courses/{id}",
     *     summary="Delete a course",
     *     tags={"Courses"},
     *     security={{"bearerAuth":{}}},
     * @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(type="integer"),
     * ),
     *     @OA\Response(response=200, description="Course deleted successfully"),
     *     @OA\Response(response=404, description="Course not found"),
     * )
     */

    public function destroy($id): JsonResponse
    {
        $course = Course::findOrFail($id);

        $course->delete();

        return response()->json([
            'message' => 'Course deleted successfully'
        ], 200);
    }
}
