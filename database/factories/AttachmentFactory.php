<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Statement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    public function definition(): array
    {

        $attachment_type = array_rand(Attachment::ATTACHMENT_MODELS);
        /** @var Model $class */
        $class = Attachment::ATTACHMENT_MODELS[$attachment_type];
        $atachment_id = $class::query()->exists()
            ? $class::query()->inRandomOrder()->first()->id
            : $class::factory()->create()->id;
        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        return [
            'basename' => $file->getClientOriginalName(),
            'path' => $file->path(),
            'extension' => $file->extension(),
            'attachment_id' => $atachment_id,
            'attachment_type' => $attachment_type,
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Attachment $attachment) {
            Storage::disk('attachment')->put(
                $attachment->path,
                'test',
            );
        });
    }
}
