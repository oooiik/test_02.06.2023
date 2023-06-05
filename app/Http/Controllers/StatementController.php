<?php

namespace App\Http\Controllers;

use App\Executor\StatementExecutor;
use App\Http\Requests\Statement\StatementIndexRequest;
use App\Http\Requests\Statement\StatementStoreRequest;
use App\Http\Requests\Statement\StatementUpdateRequest;
use App\Http\Resources\Statement\StatementResource;
use App\Models\Statement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Tag(
 *     name="Statement",
 *     description="API Endpoints of Statement"
 * )
 *
 * @method StatementExecutor executor()
 */
class StatementController extends Controller
{
    protected $executorClass = StatementExecutor::class;

    /**
     * @OA\Get(
     *      path="/api/statements",
     *      operationId="index",
     *      tags={"Statement"},
     *      summary="Get statement list",
     *      description="Returns list of statement",
     *      security={ {"bearerAuth": {} } },
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User ID",
     *          required=false,
     *          in="query",
     *          @OA\Schema( type="integer" )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent( type="array", @OA\Items( ref="#/components/schemas/StatementResource" ) ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseForbidden" ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnprocessableEntity" ),
     *      )
     * )
     */
    public function index(StatementIndexRequest $request): JsonResponse
    {
        if (Gate::denies('viewAny', Statement::class)) {
            return $this->responseForbidden('You do not have permission to view statement list');
        }

        $data = $this->executor()->index($request->validated());
        return $this->responseSuccess(StatementResource::collection($data), 'Statement list');
    }

    /**
     * @OA\Post(
     *      path="/api/statements",
     *      operationId="store",
     *      tags={"Statement"},
     *      summary="Store new statement",
     *      description="Returns statement data",
     *      security={ {"bearerAuth": {} } },
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( ref="#/components/schemas/StatementStoreRequest" ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent( ref="#/components/schemas/StatementResource" ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseForbidden" ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnprocessableEntity" ),
     *      )
     * )
     */
    public function store(StatementStoreRequest $request): JsonResponse
    {
        if (Gate::denies('create', Statement::class)) {
            return $this->responseForbidden('You do not have permission to create statement');
        }

        $model = $this->executor()->store($request->validated());
        return $this->responseCreated(new StatementResource($model), 'Statement created');
    }

    /**
     * @OA\Get(
     *      path="/api/statements/{id}",
     *      operationId="show",
     *      tags={"Statement"},
     *      summary="Get statement detail",
     *      description="Returns statement data",
     *      security={ {"bearerAuth": {} } },
     *      @OA\Parameter(
     *          name="id",
     *          description="Statement ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema( type="integer" )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent( ref="#/components/schemas/StatementResource" ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseForbidden" ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseNotFound" ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnauthorized" ),
     *      )
     * )
     */
    public function show(int $id): JsonResponse
    {
        if (Gate::denies('view', [Statement::class, $id])) {
            return $this->responseForbidden('You do not have permission to view statement');
        }

        $model = $this->executor()->show($id);
        if (!$model) {
            return $this->responseNotFound('Statement not found');
        }
        return $this->responseSuccess(new StatementResource($model), 'Statement detail');
    }

    /**
     * @OA\Put(
     *      path="/api/statements/{id}",
     *      operationId="update",
     *      tags={"Statement"},
     *      summary="Update existing statement",
     *      description="Returns updated statement data",
     *      security={ {"bearerAuth": {} } },
     *      @OA\Parameter(
     *          name="id",
     *          description="Statement ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema( type="integer" )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent( ref="#/components/schemas/StatementUpdateRequest" ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent( ref="#/components/schemas/StatementResource" ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseForbidden" ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseNotFound" ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnprocessableEntity" ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnauthorized" ),
     *      )
     * )
     */
    public function update(StatementUpdateRequest $request, int $id): JsonResponse
    {
        if (Gate::denies('update', [Statement::class, $id])) {
            return $this->responseForbidden('You do not have permission to update statement');
        }

        $model = $this->executor()->update($request->validated(), $id);
        if (!$model) {
            return $this->responseNotFound('Statement not found');
        }
        return $this->responseSuccess(new StatementResource($model), 'Statement updated');
    }

    /**
     * @OA\Delete(
     *      path="/api/statements/{id}",
     *      operationId="destroy",
     *      tags={"Statement"},
     *      summary="Delete existing statement",
     *      description="Deletes a record and returns no content",
     *      security={ {"bearerAuth": {} } },
     *      @OA\Parameter(
     *          name="id",
     *          description="Statement ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema( type="integer" )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseNoContent" ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseForbidden" ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseNotFound" ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent( ref="#/components/schemas/ResponseUnauthorized" ),
     *      )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        if (Gate::denies('delete', [Statement::class, $id])) {
            return $this->responseForbidden('You do not have permission to delete statement');
        }

        $this->executor()->destroy($id);
        return $this->responseNoContent('Statement deleted');
    }
}
