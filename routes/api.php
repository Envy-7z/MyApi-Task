<?php

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="API documentation for My Application",
 *     @OA\Contact(
 *         email="tess@example.com"
 *     )
 * )
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserProfileController;


// Authentication routes

/**
 * @OA\Post(
 *     path="/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid.")
 *         )
 *     )
 * )
 */
Route::post('/register', [AuthController::class, 'register']);

/**
 * @OA\Post(
 *     path="/login",
 *     summary="Login to the application",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User logged in successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="eyJ0eXAi..."),
 *             @OA\Property(property="token_type", type="string", example="bearer"),
 *             @OA\Property(property="expires_in", type="integer", example="3600")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */
Route::post('/login', [AuthController::class, 'login']);

/**
 * @OA\Post(
 *     path="/logout",
 *     summary="Logout from the application",
 *     tags={"Authentication"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="User logged out successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Successfully logged out")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// User profile routes (protected by auth:api middleware)
Route::middleware('auth:api')->group(function () {

    /**
     * @OA\Get(
     *     path="/profile",
     *     summary="Get authenticated user's profile",
     *     tags={"User Profile"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user's profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    Route::get('/profile', [UserProfileController::class, 'show']);

    /**
     * @OA\Put(
     *     path="/profile",
     *     summary="Update authenticated user's profile",
     *     tags={"User Profile"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated user profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    Route::put('/profile', [UserProfileController::class, 'update']);

    /**
     * @OA\Delete(
     *     path="/profile",
     *     summary="Delete authenticated user's profile",
     *     tags={"User Profile"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    Route::delete('/profile', [UserProfileController::class, 'destroy']);
});


// Task routes (protected by auth:api middleware)
Route::middleware('auth:api')->group(function () {

    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="List all tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    Route::get('/tasks', [TaskController::class, 'index']);

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description"},
     *             @OA\Property(property="title", type="string", example="Task Title"),
     *             @OA\Property(property="description", type="string", example="Task Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created",
     *         @OA\JsonContent(
     *             @OA\Property(property="task", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     )
     * )
     */
    Route::post('/tasks', [TaskController::class, 'store']);

    /**
     * @OA\Get(
     *     path="/tasks/{id}",
     *     summary="Get a task by ID",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task found",
 *         @OA\JsonContent(
 *             @OA\Property(property="task", ref="#/components/schemas/Task")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Task not found")
 *         )
 *     )
 * )
 */
Route::get('/tasks/{id}', [TaskController::class, 'show']);

/**
 * @OA\Put(
 *     path="/tasks/{id}",
 *     summary="Update a task by ID",
 *     tags={"Tasks"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description"},
 *             @OA\Property(property="title", type="string", example="Updated Task Title"),
 *             @OA\Property(property="description", type="string", example="Updated Task Description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="task", ref="#/components/schemas/Task")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Task not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid.")
 *         )
 *     )
 * )
 */
Route::put('/tasks/{id}', [TaskController::class, 'update']);

/**
 * @OA\Delete(
 *     path="/tasks/{id}",
 *     summary="Delete a task by ID",
 *     tags={"Tasks"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Task ID",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task deleted",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Task deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Task not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Task not found")
 *         )
 *     )
 * )
 */
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
});
