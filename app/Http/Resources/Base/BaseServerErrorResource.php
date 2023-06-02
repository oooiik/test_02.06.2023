<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseServerErrorResource",
 *     description="Base server error resource",
 *     schema="BaseServerErrorResource",
 *     example={
 *         "message":"Server error",
 *         "data":null,
 *         "success":false
 *     }
 * )
 */
class BaseServerErrorResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Server error', $status = 500, $success = true)
    {
        parent::__construct($resource, $message, $status, $success);
    }
}
