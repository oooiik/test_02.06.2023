<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait TraitJsonResponse
{
    /**
     * @OA\Schema (
     *     title="ResponseSuccess",
     *     description="Response success",
     *     schema="ResponseSuccess",
     *     example={
     *         "success":true,
     *         "message":"Success",
     *         "data":{}
     *     }
     * )
     */
    protected function responseSuccess($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @OA\Schema (
     *     title="ResponseError",
     *     description="Response error",
     *     schema="ResponseError",
     *     example={
     *         "success":false,
     *         "message":"Error",
     *     }
     * )
     */
    protected function responseError($message = null, $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * @OA\Schema (
     *     title="ResponseNotFound",
     *     description="Response not found",
     *     schema="ResponseNotFound",
     *     example={
     *         "success":false,
     *         "message":"Not Found",
     *     }
     * )
     */
    protected function responseNotFound($message = null): JsonResponse
    {
        return $this->responseError($message, 404);
    }

    /**
     * @OA\Schema (
     *     title="ResponseUnauthorized",
     *     description="Response unauthorized",
     *     schema="ResponseUnauthorized",
     *     example={
     *         "success":false,
     *         "message":"Unauthorized",
     *     }
     * )
     */
    protected function responseUnauthorized($message = null): JsonResponse
    {
        return $this->responseError($message, 401);
    }

    /**
     * @OA\Schema (
     *     title="ResponseForbidden",
     *     description="Response forbidden",
     *     schema="ResponseForbidden",
     *     example={
     *         "success":false,
     *         "message":"Forbidden",
     *     }
     * )
     */
    protected function responseForbidden($message = null): JsonResponse
    {
        return $this->responseError($message, 403);
    }

    /**
     * @OA\Schema (
     *     title="ResponseInternalError",
     *     description="Response internal error",
     *     schema="ResponseInternalError",
     *     example={
     *         "success":false,
     *         "message":"Internal Error",
     *     }
     * )
     */
    protected function responseInternalError($message = null): JsonResponse
    {
        return $this->responseError($message, 500);
    }

    /**
     * @OA\Schema (
     *     title="ResponseCreated",
     *     description="Response created",
     *     schema="ResponseCreated",
     *     example={
     *         "success":true,
     *         "message":"Created",
     *         "data":{}
     *     }
     * )
     */
    protected function responseCreated($data, $message = null): JsonResponse
    {
        return $this->responseSuccess($data, $message, 201);
    }

    /**
     * @OA\Schema (
     *     title="ResponseNoContent",
     *     description="Response no content",
     *     schema="ResponseNoContent",
     *     example={
     *         "success":true,
     *         "message":"No Content",
     *     }
     * )
     */
    protected function responseNoContent($message = null): JsonResponse
    {
        return $this->responseSuccess([], $message, 204);
    }

    /**
     * @OA\Schema (
     *     title="ResponseBadRequest",
     *     description="Response bad request",
     *     schema="ResponseBadRequest",
     *     example={
     *         "success":false,
     *         "message":"Bad Request",
     *     }
     * )
     */
    protected function responseBadRequest($message = null): JsonResponse
    {
        return $this->responseError($message);
    }

    /**
     * @OA\Schema (
     *     title="ResponseUnprocessableEntity",
     *     description="Response unprocessable entity",
     *     schema="ResponseUnprocessableEntity",
     *     example={
     *         "success":false,
     *         "message":"Unprocessable Entity",
     *         "errors":{}
     *     }
     * )
     */
    protected function responseUnprocessableEntity($message = null): JsonResponse
    {
        return $this->responseError($message, 422);
    }

    /**
     * @OA\Schema (
     *     title="ResponseServerError",
     *     description="Response server error",
     *     schema="ResponseServerError",
     *     example={
     *         "success":false,
     *         "message":"Server Error",
     *     }
     * )
     */
    protected function responseServerError($message = null): JsonResponse
    {
        return $this->responseError($message, 500);
    }

    /**
     * @OA\Schema (
     *     title="ResponseDownload",
     *     description="Response download",
     *     schema="ResponseDownload"
     * )
     */
    protected function responseDownload($file, $name): BinaryFileResponse
    {
        return response()->download($file, $name);
    }
}
