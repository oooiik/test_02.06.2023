<?php

namespace App\Http\Controllers;

use App\Executor\PermissionExecutor;
use App\Http\Resources\Permission\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="API Endpoints of Permissions"
 * )
 *
 * @method PermissionExecutor executor()
 */
class PermissionController extends Controller
{
    protected $executorClass = PermissionExecutor::class;

    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     operationId="permissionsIndex",
     *     tags={"Permissions"},
     *     summary="Get all permissions",
     *     description="Returns all permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property( property="data", type="array", @OA\Items(ref="#/components/schemas/PermissionResource")),
     *             @OA\Property( property="message", type="string", example="Permissions retrieved successfully."),
     *             @OA\Property( property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        if (Gate::denies('index', Permission::class)) $this->responseUnauthorized();

        return $this->responseSuccess(PermissionResource::collection($this->executor()->index()), 'Permissions retrieved successfully.');
    }

    /**
     * @OA\Get(
     *     path="/api/permissions/{id}",
     *     operationId="permissionsShow",
     *     tags={"Permissions"},
     *     summary="Get permission by id",
     *     description="Returns permission by id",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter( name="id", description="Permission id", required=true, in="path", @OA\Schema( type="integer" )),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property( property="data", ref="#/components/schemas/PermissionResource"),
     *             @OA\Property( property="message", type="string", example="Permission retrieved successfully."),
     *             @OA\Property( property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource not found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseUnauthorized")
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        if (Gate::denies('show', Permission::class)) $this->responseUnauthorized();

        $permission = $this->executor()->show($id);
        if (empty($permission)) $this->responseNotFound('Permission not found.');
        return $this->responseSuccess(new PermissionResource($permission), 'Permission retrieved successfully.');
    }
}
