<?php

namespace App\Http\Controllers;

use App\Executor\PermissionExecutor;
use App\Http\Resources\Permission\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
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
     *     )
     * )
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $permission = $this->executor()->show($id);
        if (empty($permission)) $this->responseNotFound('Permission not found.');
        return $this->responseSuccess(new PermissionResource($permission), 'Permission retrieved successfully.');
    }
}
