<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $basename
 * @property string $path
 * @property string $extension
 * @property int $attachment_id
 * @property string $attachment_type
 *
 * @property-read Model $attachment
 */
class Attachment extends Model
{
    use HasFactory, SoftDeletes;

    public const ATTACHMENT_MODELS = [
        'statement' => Statement::class,
    ];

    protected $fillable = [
        'basename',
        'path',
        'extension',
        'attachment_id',
        'attachment_type',
    ];

    public function attachment(): MorphTo
    {
        return $this->morphTo();
    }

    public static function makeUploadedFile(UploadedFile $file, int $attachment_id, Model|string $attachment_type): array
    {
        if ($attachment_type instanceof Model) {
            $attachment_type = $attachment_type->getMorphClass();
        } elseif (is_string($attachment_type)) {
            $attachment_type = self::ATTACHMENT_MODELS[$attachment_type];
            if (!class_exists($attachment_type)) {
                throw new \InvalidArgumentException('Invalid attachment type');
            }
        }
        $makePath = array_search($attachment_type, self::ATTACHMENT_MODELS) . '/' . $attachment_id;
        $path = Storage::disk('attachment')->putFile($makePath, $file);
        return [
            'basename' => $file->getClientOriginalName(),
            'path' => $path,
            'extension' => $file->extension(),
            'attachment_id' => $attachment_id,
            'attachment_type' => $attachment_type,
        ];
    }

    public static function createUploadedFile(UploadedFile $file, int $attachment_id, Model|string $attachment_type): self
    {
        return self::create(self::makeUploadedFile($file, $attachment_id, $attachment_type));
    }
}
