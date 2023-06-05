<?php

namespace App\Http\Controllers;

use App\Executor\RoleExecutor;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Role",
 *     description="API Endpoints of Role"
 * )
 *
 * @method RoleExecutor executor()
 */
class RoleController extends Controller
{
    protected $executorClass = RoleExecutor::class;

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Get all roles",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/RoleResource")
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
        if (Gate::denies('viewAny', Role::class)) {
            $this->responseForbidden();
        }

        return $this->responseSuccess(
            RoleResource::collection($this->executor()->index()),
            'Roles retrieved successfully'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Create a role",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         description="Role object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoleStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property ( property="success", type="boolean", example="true" ),
     *             @OA\Property ( property="message", type="string", example="Role created successfully" ),
     *             @OA\Property ( property="data", ref="#/components/schemas/RoleResource" )
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
    public function store(RoleStoreRequest $request): JsonResponse
    {
        if (Gate::denies('create', Role::class)) {
            $this->responseForbidden();
        }

        $role = $this->executor()->store($request->all());
        return $this->responseCreated(new RoleResource($role), 'Role created successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Get a role by id",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Role id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property ( property="success", type="boolean", example="true" ),
     *             @OA\Property ( property="message", type="string", example="Role retrieved successfully" ),
     *             @OA\Property ( property="data", ref="#/components/schemas/RoleResource" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        if (Gate::denies('view', Role::class)) {
            $this->responseForbidden();
        }

        $role = $this->executor()->show($id);
        if (is_null($role)) {
            return $this->responseNotFound('Role not found');
        }
        return $this->responseSuccess(new RoleResource($role), 'Role retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Update a role by id",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Role id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\RequestBody(
     *         description="Role object that needs to be updated",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RoleUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property ( property="success", type="boolean", example="true" ),
     *             @OA\Property ( property="message", type="string", example="Role updated successfully" ),
     *             @OA\Property ( property="data", ref="#/components/schemas/RoleResource" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
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
    public function update(RoleUpdateRequest $request, $id): JsonResponse
    {
        if (Gate::denies('update', Role::class)) {
            $this->responseForbidden();
        }

        $role = $this->executor()->update($request->all(), $id);
        if (is_null($role)) {
            return $this->responseNotFound('Role not found');
        }
        return $this->responseSuccess(new RoleResource($role), 'Role updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Delete a role by id",
     *     tags={"Role"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Role id",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property ( property="success", type="boolean", example="true" ),
     *             @OA\Property ( property="message", type="string", example="Role deleted successfully" )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseForbidden")
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        if (Gate::denies('delete', Role::class)) {
            $this->responseForbidden();
        }

        $role = $this->executor()->destroy($id);
        if (is_null($role)) {
            return $this->responseNotFound('Role not found');
        }
        return $this->responseSuccess(null, 'Role deleted successfully', 204);
    }
}
