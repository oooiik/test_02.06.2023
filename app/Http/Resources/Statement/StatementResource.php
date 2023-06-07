<?php

namespace App\Http\Resources\Statement;

use App\Http\Resources\Attachment\AttachmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="StatementResource",
 *     description="Statement resource",
 *     @OA\Property ( property="id", type="integer", example="1" ),
 *     @OA\Property ( property="title", type="string", example="title" ),
 *     @OA\Property ( property="number", type="integer", example="1" ),
 *     @OA\Property ( property="date", type="string", example="2021-01-01" ),
 *     @OA\Property ( property="description", type="string", example="description" ),
 *     @OA\Property ( property="user_id", type="integer", example="1" ),
 *     @OA\Property ( property="created_at", type="string", example="2021-01-01 00:00:00" ),
 *     @OA\Property ( property="updated_at", type="string", example="2021-01-01 00:00:00" )
 * )
 *
 * @property int $id
 * @property string $title
 * @property int $number
 * @property string $date
 * @property string $description
 * @property int $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class StatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'number' => $this->number,
            'date' => $this->date,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
        ];
    }
}
