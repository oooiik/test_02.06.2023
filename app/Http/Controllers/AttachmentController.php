<?php

namespace App\Http\Controllers;

use App\Executor\AttachmentExecutor;
use App\Http\Requests\Attachment\AttachmentStoreRequest;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @OA\Tag(
 *     name="Attachment",
 *     description="Attachment API endpoints"
 * )
 *
 * @method AttachmentExecutor executor()
 */
class AttachmentController extends Controller
{
    protected $executorClass = AttachmentExecutor::class;

    /**
     * @OA\Post (
     *     path="/api/attachments",
     *     summary="Create attachment",
     *     tags={"Attachment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         description="Attachment object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"file", "attachment_id", "attachment_type"},
     *                 ref="#/components/schemas/AttachmentStoreRequest",
     *                 @OA\Property( property="file", type="file", format="binary" )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/AttachmentResource"),
     *             @OA\Property(property="message", type="string", example="Attachment created"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent( ref="#/components/schemas/ResponseForbidden")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent( ref="#/components/schemas/ResponseUnprocessableEntity")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent( ref="#/components/schemas/ResponseUnauthorized")
     *     )
     * )
     */
    public function store(AttachmentStoreRequest $request): JsonResponse
    {
        if (Gate::denies('create', [
            Attachment::class,
            $request->get('attachment_id'),
            $request->get('attachment_type')
        ])) {
            return $this->responseForbidden('You are not authorized to create attachment');
        }

        $model = $this->executor()->store($request->validated());
        return $this->responseCreated(new AttachmentResource($model), 'Attachment created');
    }

    /**
     * @OA\Get(
     *     path="/api/attachments/{id}",
     *     summary="Get attachment detail",
     *     tags={"Attachment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/AttachmentResource")
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        if (Gate::denies('view', [Attachment::class, $id])) {
            return $this->responseForbidden('You are not authorized to view attachment');
        }

        $model = $this->executor()->show($id);
        if (!$model) {
            return $this->responseNotFound('Attachment not found');
        }

        return $this->responseSuccess(new AttachmentResource($model), 'Attachment detail');
    }

    /**
     * @OA\Delete (
     *     path="/api/attachments/{id}",
     *     summary="Delete attachment",
     *     tags={"Attachment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNoContent")
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseServerError")
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        if (Gate::denies('delete', [Attachment::class, $id])) {
            return $this->responseForbidden('You are not authorized to delete attachment');
        }

        $model = $this->executor()->destroy($id);
        if ($model === null) {
            return $this->responseNotFound('Attachment not found');
        }
        if (!$model) {
            return $this->responseServerError('Attachment failed to delete');
        }
        return $this->responseNoContent('Attachment deleted');
    }

    /**
     * @OA\Get(
     *     path="/api/attachments/{id}/download",
     *     summary="Download attachment",
     *     tags={"Attachment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Attachment ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/octet-stream",
     *             @OA\Schema(
     *                 type="file"
     *             )
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *     )
     * )
     */
    public function download($id): BinaryFileResponse|JsonResponse
    {
        if (Gate::denies('view', [Attachment::class, $id])) {
            return $this->responseForbidden('You are not authorized to view attachment');
        }

        $download = $this->executor()->download($id);
        if (!$download) {
            return $this->responseNotFound('Attachment not found');
        }

        return $this->responseDownload($download['path'], $download['name']);
    }
}
