<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait TraitJsonResponse
{
    protected function responseSuccess($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function responseError($message = null, $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    protected function responseNotFound($message = null): JsonResponse
    {
        return $this->responseError($message, 404);
    }

    protected function responseUnauthorized($message = null): JsonResponse
    {
        return $this->responseError($message, 401);
    }

    protected function responseForbidden($message = null): JsonResponse
    {
        return $this->responseError($message, 403);
    }

    protected function responseInternalError($message = null): JsonResponse
    {
        return $this->responseError($message, 500);
    }

    protected function responseCreated($data, $message = null): JsonResponse
    {
        return $this->responseSuccess($data, $message, 201);
    }

    protected function responseNoContent($message = null): JsonResponse
    {
        return $this->responseSuccess([], $message, 204);
    }

    protected function responseBadRequest($message = null): JsonResponse
    {
        return $this->responseError($message, 400);
    }

    protected function responseUnprocessableEntity($message = null): JsonResponse
    {
        return $this->responseError($message, 422);
    }

}
