<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Statement;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    public function run(): void
    {
        Statement::all()->each(function (Statement $statement): void {
            Attachment::factory()->count(rand(0, 2))->create([
                'attachment_id' => $statement->id,
                'attachment_type' => Statement::class,
            ]);
        });
    }
}
