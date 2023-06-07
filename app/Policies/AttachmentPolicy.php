<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class AttachmentPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, int $attachment_id): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        $attachment = Attachment::query()->find($attachment_id);
        if (!$attachment) {
            return false;
        }
        $class = Attachment::ATTACHMENT_MODELS[$attachment->attachment_type];
        if (!$class) {
            return false;
        }
        // check policy for the class
        return (bool)Gate::allows('view', [$class, $attachment->attachment_id]);
    }

    public function create(User $user, int $attachment_id, string $attachment_type): bool
    {
        /** @var ?Model $class */
        $class = Attachment::ATTACHMENT_MODELS[$attachment_type];
        if (!$class) {
            return false;
        }
        $model = $class::query()->find($attachment_id);
        if (!$model) {
            return false;
        }
        return Gate::allows('update', [$class, $attachment_id]);
    }

    public function update(User $user, Attachment $attachment): bool
    {
        return false;
    }

    public function delete(User $user, $attachment_id): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        $attachment = Attachment::query()->find($attachment_id);
        if (!$attachment) {
            return false;
        }
        $class = Attachment::ATTACHMENT_MODELS[$attachment->attachment_type];
        if (!$class) {
            return false;
        }
        return Gate::allows('delete', [$class, $attachment->attachment_id]);
    }

    public function restore(User $user, Attachment $attachment): bool
    {
        return false;
    }

    public function forceDelete(User $user, Attachment $attachment): bool
    {
        return false;
    }
}
