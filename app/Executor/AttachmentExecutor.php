<?php

namespace App\Executor;

use App\Models\Attachment;
use App\Traits\TraitCacheForCrudExecutor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Executor<Attachment>
 */
class AttachmentExecutor extends Executor
{
    use TraitCacheForCrudExecutor;

    public function model(): Model
    {
        return new Attachment();
    }

    public function modelName(): string
    {
        return strtolower(class_basename($this->model()));
    }

    public function store(array $validated): Model
    {
        /** @var Attachment $model */
        $model = $this->model()::createUploadedFile(
            $validated['file'],
            $validated['attachment_id'],
            $validated['attachment_type']
        );
        return $model;
    }

    public function show($id): ?Model
    {
        return $this->cacheGetOrSet($id, function () use ($id) {
            return $this->model()::find($id);
        });
    }

    public function destroy($id): bool|null
    {
        $model = $this->show($id);
        if (!$model) {
            return null;
        }
        $this->cacheForgot('show_' . $id);
        return $model->delete();
    }

    public function download($id): ?array
    {
        $model = $this->show($id);
        if (!$model) {
            return null;
        }
        if (!Storage::disk('attachment')->exists($model->path)) {
            return null;
        }

        return [
            'name' => $model->basename,
            'path' => Storage::disk('attachment')->path($model->path),
        ];
    }
}
