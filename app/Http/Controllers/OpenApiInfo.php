<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Student Registration API",
 *     version="1.0.0",
 *     description="A RESTful API for student course registration"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class OpenApiInfo {}
