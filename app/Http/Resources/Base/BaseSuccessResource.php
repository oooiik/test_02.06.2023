<?php

namespace App\Http\Resources\Base;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="BaseSuccessResource",
 *     description="Base success resource",
 *     schema="BaseSuccessResource",
 *     example={
 *         "message":"Success",
 *         "data":null,
 *         "success":true
 *     }
 * )
 */
class BaseSuccessResource extends BaseResource
{
    public function __construct($resource = null, $message = 'Success', $status = 200, $success = true)
    {
        parent::__construct($resource, $message, $status, $success);
    }
}
