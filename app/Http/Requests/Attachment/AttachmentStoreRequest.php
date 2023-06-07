<?php

namespace App\Http\Requests\Attachment;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     title="AttachmentStoreRequest",
 *     description="Attachment store request",
 *     schema="AttachmentStoreRequest",
 *     required={"file", "attachment_id", "attachment_type"},
 *     @OA\Property( property="file", type="file", format="binary" ),
 *     @OA\Property( property="attachment_id", type="integer", example="1" ),
 *     @OA\Property( property="attachment_type", type="string", example="statement" )
 * )
 */
class AttachmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file',
            'attachment_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $attachment_type = $this->request->get('attachment_type');
                    /** @var ?Model $class */
                    $class = Attachment::ATTACHMENT_MODELS[$attachment_type] ?? null;
                    if (empty($class)) {
                        $fail('Attachment type is not valid');
                        return;
                    }
                    $has = $class::query()->where('id', $value)->exists();
                    if (!$has) {
                        $fail('Attachment id is not valid');
                    }
                },
            ],
            'attachment_type' => [
                'required',
                'string',
                Rule::in(array_keys(Attachment::ATTACHMENT_MODELS)),
            ],
        ];
    }
}
