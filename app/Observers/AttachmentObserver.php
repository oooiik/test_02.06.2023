<?php

namespace App\Observers;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentObserver
{
    public function created(Attachment $attachment): void
    {
        //
    }

    public function updated(Attachment $attachment): void
    {
        //
    }

    public function deleted(Attachment $attachment): void
    {
        //
    }

    public function restored(Attachment $attachment): void
    {
        //
    }

    public function forceDeleted(Attachment $attachment): void
    {
        Storage::disk('attachment')->delete($attachment->path);
    }
}
