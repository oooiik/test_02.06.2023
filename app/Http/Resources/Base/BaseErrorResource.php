<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseErrorResource",
 *     description="Base error resource",
 *     schema="BaseErrorResource",
 *     example={
 *         "message":"Error",
 *         "data":null,
 *         "success":false
 *     }
 * )
 */
class BaseErrorResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Error', $status = 400, $success = false)
    {
        parent::__construct($resource, $message, $status, $success);
    }
}
