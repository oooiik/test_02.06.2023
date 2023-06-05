<?php

namespace App\Http\Controllers;

use App\Executor\AuthExecutor;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints of Auth"
 * )
 *
 * @method AuthExecutor executor()
 */
class AuthController extends Controller
{
    public $executorClass = AuthExecutor::class;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     description="Login",
     *     operationId="authLogin",
     *     @OA\RequestBody(
     *         description="Login",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthLoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged in successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *                 @OA\Property(property="token", type="string", example="token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnprocessableEntity")
     *    )
     * )
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $logged = $this->executor()->login($request->validated());
        if (!$logged) {
            return $this->responseUnauthorized('Invalid credentials');
        }
        return $this->responseSuccess([
            'user' => new UserResource($logged['user']),
            'token' => $logged['token']
        ], 'Logged in successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Logout",
     *     operationId="authLogout",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully"),
     *             @OA\Property(property="data", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        $this->executor()->logout();
        return $this->responseSuccess(null, 'Logged out successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Auth"},
     *     summary="Me",
     *     description="Me",
     *     operationId="authMe",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="message", type="string", example="User retrieved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->executor()->me();
        if (!$user) {
            return $this->responseUnauthorized('Unauthorized');
        }
        return $this->responseSuccess(new UserResource($user), 'User retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Register",
     *     description="Register",
     *     operationId="authRegister",
     *     @OA\RequestBody(
     *         description="Register",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthRegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource"),
     *                 @OA\Property(property="token", type="string", example="token"),
     *             ),
     *             @OA\Property(property="message", type="string", example="Registered successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseError")
     *     )
     * )
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $this->executor()->register($request->validated());
        if (!$data) {
            return $this->responseError('Failed to register', 500);
        }
        return $this->responseCreated([
            'user' => new UserResource($data['user']),
            'token' => $data['token']
        ], 'Registered successfully');
    }
}
