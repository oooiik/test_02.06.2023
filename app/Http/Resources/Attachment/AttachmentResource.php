<?php

namespace App\Http\Resources\Attachment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="AttachmentResource",
 *     description="Attachment resource",
 *     schema="AttachmentResource"
 * )
 *
 * @property-read int $id
 * @property-read string $basename
 * @property-read string $path
 * @property-read string $extension
 * @property-read int $attachment_id
 * @property-read string $attachment_type
 * @property-read string $created_at
 * @property-read string $updated_at
 */
class AttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'basename' => $this->basename,
            'path' => $this->path,
            'extension' => $this->extension,
        ];
    }
}
