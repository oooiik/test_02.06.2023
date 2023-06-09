<?php

namespace App\Http\Controllers;

use App\Executor\UserExecutor;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints of Users"
 * )
 *
 * @method UserExecutor executor()
 */
class UserController extends Controller
{
    protected $executorClass = UserExecutor::class;

    /**
     * @OA\Get (
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     description="Get all users",
     *     operationId="usersIndex",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             ),
     *             @OA\Property( property="message", type="string", example="Users list" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        if (Gate::denies('viewAny', User::class)) {
            $this->responseForbidden();
        }

        $data = $this->executor()->index();
        return $this->responseSuccess(UserResource::collection($data), 'Users list');
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create user",
     *     description="Create user",
     *     operationId="usersStore",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         description="Create user",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/UserResource"
     *             ),
     *             @OA\Property( property="message", type="string", example="User created" ),
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
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        if (Gate::denies('create', User::class)) {
            $this->responseForbidden();
        }

        $created = $this->executor()->store($request->validated());
        return $this->responseSuccess(new UserResource($created), 'User created', 201);
    }

    /**
     * @OA\Get (
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user",
     *     description="Get user",
     *     operationId="usersShow",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/UserResource"
     *             ),
     *             @OA\Property( property="message", type="string", example="User info" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        if (Gate::denies('view', [User::class, $id])) {
            $this->responseForbidden();
        }

        $model = $this->executor()->show($id);
        if (!$model) {
            return $this->responseNotFound('User not found');
        }
        return $this->responseSuccess(new UserResource($model), 'User info');
    }

    /**
     * @OA\Put(
     *    path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Update user",
     *     operationId="usersUpdate",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Update user",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/UserResource"
     *             ),
     *             @OA\Property( property="message", type="string", example="User updated" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnprocessableEntity")
     *     )
     * )
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        if (Gate::denies('update', [User::class, $id])) {
            $this->responseForbidden();
        }

        $model = $this->executor()->update($request->validated(), $id);
        if (!$model) {
            return $this->responseNotFound('User not found');
        }
        return $this->responseSuccess(new UserResource($model), 'User updated');
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Delete user",
     *     operationId="usersDestroy",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property( property="data", type="null" ),
     *             @OA\Property( property="message", type="string", example="User deleted" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        if (Gate::denies('delete', [User::class, $id])) {
            $this->responseForbidden();
        }

        $success = $this->executor()->destroy($id);
        if (!$success) {
            return $this->responseNotFound('User not found');
        }
        return $this->responseSuccess(null, 'User deleted', 204);
    }
}
