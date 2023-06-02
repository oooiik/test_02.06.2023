<?php

namespace App\Http\Controllers;

use App\Executor\AuthExecutor;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Auth\AuthRegisterResource;
use App\Http\Resources\Base\BaseResource;
use App\Http\Resources\Base\BaseServerErrorResource;
use App\Http\Resources\Base\BaseSuccessResource;
use App\Http\Resources\Base\BaseUnauthorizedResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;

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
     *         @OA\JsonContent(ref="#/components/schemas/AuthLoginResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnprocessableEntityResource")
     *    )
     * )
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $logged = $this->executor()->login($request->validated());
        if (!$logged) return BaseUnauthorizedResource::resource();
        $resource = new AuthLoginResource($logged);
        return $this->responseSuccess($resource, 'Logged in successfully');
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
     *         @OA\JsonContent(ref="#/components/schemas/BaseSuccessResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        $this->executor()->logout();
        return BaseResource::resource([], 'Logged out successfully');
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
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *    ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->executor()->me();
        if (!$user) return BaseUnauthorizedResource::resource();
        return BaseSuccessResource::resource(new UserResource($user), 'User retrieved successfully');
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
     *         @OA\JsonContent(ref="#/components/schemas/AuthRegisterResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/BaseServerErrorResource")
     *     )
     * )
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $this->executor()->register($request->validated());
        if (!$data) return BaseServerErrorResource::resource(null, 'Failed to register');
        return AuthRegisterResource::resource($data);
    }
}
