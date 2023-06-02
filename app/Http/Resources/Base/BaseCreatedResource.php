<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseCreatedResource",
 *     description="Base created resource",
 *     schema="BaseCreatedResource",
 *     example={
 *         "message":"Created",
 *         "data":{},
 *         "success":true
 *     }
 * )
 */
class BaseCreatedResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Created', $status = 201, $success = true)
    {
        parent::__construct($resource, $message, $status, $success);
    }
}
