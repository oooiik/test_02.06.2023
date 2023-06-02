<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseUnprocessableEntityResource",
 *     description="Base unprocessable entity resource",
 *     schema="BaseUnprocessableEntityResource",
 *     example={
 *         "message":"Unprocessable entity",
 *         "errors":{
 *             "field":{"Error message"}
 *         },
 *         "success":false
 *     }
 * )
 */
class BaseUnprocessableEntityResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Unprocessable entity', $status = 422, $success = false)
    {
        parent::__construct($resource, $message, $status, $success);
    }
}
