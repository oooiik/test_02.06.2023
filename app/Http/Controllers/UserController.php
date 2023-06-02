<?php

namespace App\Http\Controllers;

use App\Executor\UserExecutor;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\Base\BaseNotFoundResource;
use App\Http\Resources\Base\BaseSuccessResource;
use App\Http\Resources\User\UserIndexResource;
use App\Http\Resources\User\UserShowResource;
use App\Http\Resources\User\UserStoreResource;
use App\Http\Resources\User\UserUpdateResource;
use Illuminate\Http\JsonResponse;

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
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\PathItem ( ref="#/components/schemas/UserResource" )
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
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $data = $this->executor()->index();
        return UserIndexResource::resource($data);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create user",
     *     description="Create user",
     *     operationId="usersStore",
     *     @OA\RequestBody(
     *         description="Create user",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/UserStoreResource")
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
     *     )
     * )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $created = $this->executor()->store($request->validated());
        return UserStoreResource::resource($created);
    }

    /**
     * @OA\Get (
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user",
     *     description="Get user",
     *     operationId="usersShow",
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
     *         @OA\JsonContent(ref="#/components/schemas/UserShowResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/BaseNotFoundResource")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $model = $this->executor()->show($id);
        if (!$model) return BaseNotFoundResource::resource(null, 'User not found');
        return UserShowResource::resource($model);
    }

    /**
     * @OA\Put(
     *    path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     description="Update user",
     *     operationId="usersUpdate",
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
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(ref="#/components/schemas/UserUpdateResource")
     *  ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/BaseNotFoundResource")
     * )
     * )
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $model = $this->executor()->update($request->validated(), $id);
        if (!$model) return BaseNotFoundResource::resource(null, 'User not found');
        return UserUpdateResource::resource($model);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Delete user",
     *     operationId="usersDestroy",
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
     *         @OA\JsonContent(ref="#/components/schemas/BaseSuccessResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/BaseUnauthorizedResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(ref="#/components/schemas/BaseNotFoundResource")
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $success = $this->executor()->destroy($id);
        if (!$success) return BaseNotFoundResource::resource(null, 'User not found');
        return BaseSuccessResource::resource(null, 'User deleted successfully');
    }
}
