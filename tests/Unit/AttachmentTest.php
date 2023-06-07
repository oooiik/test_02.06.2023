<?php

namespace Tests\Unit;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AttachmentTest extends TestCase
{
    public function test_factory(): void
    {
        $make = Attachment::factory()->make();
        $this->assertNotEmpty($make);

        $create = Attachment::factory()->create();
        $this->assertNotEmpty($create);
        $this->assertDatabaseHas(
            'attachments',
            $create->makeHidden(['updated_at', 'created_at'])->toArray()
        );
    }

    public function test_store_method_success(): void
    {
        $this->actingAs($this->user);
        $make = Attachment::factory()->make();
        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        $response = $this->call(
            'POST',
            route('attachments.store'),
            [
                'attachment_id' => $make->attachment_id,
                'attachment_type' => $make->attachment_type,
            ],
            [],
            [
                'file' => $file,
            ],
            [],
            'application/json'
        );
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'basename',
                'path',
                'extension',
            ],
            'message',
            'success',
        ]);
        $this->assertDatabaseHas(
            'attachments',
            $response->json('data')
        );
    }

    public function test_store_method_401(): void
    {
        $make = Attachment::factory()->make();
        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        $response = $this->postJson(route('attachments.store'));
        $response->assertStatus(401);
    }

    public function test_store_method_403(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('user');

        $class = Attachment::ATTACHMENT_MODELS[array_rand(Attachment::ATTACHMENT_MODELS)];
        $attachment_id = $class::factory()->create([
            'user_id' => $user->id,
        ])->id;
        $attachment_type = $class;

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $user2->assignRole('user');
        $this->actingAs($user2);

        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        $response = $this->call(
            'POST',
            route('attachments.store'),
            [
                'attachment_id' => $attachment_id,
                'attachment_type' => array_search($attachment_type, Attachment::ATTACHMENT_MODELS),
            ],
            [],
            [
                'file' => $file,
            ],
            [],
            'application/json'
        );
        $response->assertStatus(403);
    }

    public function test_store_method_422(): void
    {
        $this->actingAs($this->user);
        $response = $this->postJson(route('attachments.store'));
        $response->assertStatus(422);
    }

    public function test_show_method_success(): void
    {
        $this->actingAs($this->user);
        $attachment = Attachment::factory()->create();
        $response = $this->getJson(route('attachments.show', $attachment->id));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'basename',
                'path',
                'extension',
            ],
            'message',
            'success',
        ]);
        $response->assertJson([
            'data' => [
                'basename' => $attachment->basename,
                'extension' => $attachment->extension,
            ],
        ]);
    }

    public function test_show_method_401(): void
    {
        $attachment = Attachment::factory()->create();
        $response = $this->getJson(route('attachments.show', $attachment->id));
        $response->assertStatus(401);
    }

    public function test_show_method_403(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('user');

        $class = Attachment::ATTACHMENT_MODELS[array_rand(Attachment::ATTACHMENT_MODELS)];
        $attachment_id = $class::factory()->create([
            'user_id' => $user->id,
        ])->id;

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $user2->assignRole('user');
        $this->actingAs($user2);

        $response = $this->getJson(route('attachments.show', $attachment_id));
        $response->assertStatus(403);
    }

    public function test_show_method_404(): void
    {
        $this->actingAs($this->user);
        $response = $this->getJson(route('attachments.show', 0));
        $response->assertStatus(404);
    }

    public function test_destroy_method_success(): void
    {
        $this->actingAs($this->user);
        $attachment = Attachment::factory()->create();
        $response = $this->deleteJson(route('attachments.destroy', $attachment->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('attachments', ['id' => $attachment->id, 'deleted_at' => null]);
    }

    public function test_destroy_method_401(): void
    {
        $attachment = Attachment::factory()->create();
        $response = $this->deleteJson(route('attachments.destroy', $attachment->id));
        $response->assertStatus(401);
    }

    public function test_destroy_method_403(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('user');

        $class = Attachment::ATTACHMENT_MODELS[array_rand(Attachment::ATTACHMENT_MODELS)];
        $attachment_id = $class::factory()->create([
            'user_id' => $user->id,
        ])->id;

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $user2->assignRole('user');
        $this->actingAs($user2);

        $response = $this->deleteJson(route('attachments.destroy', $attachment_id));
        $response->assertStatus(403);
    }

    public function test_destroy_method_404(): void
    {
        $this->actingAs($this->user);
        $response = $this->deleteJson(route('attachments.destroy', 0));
        $response->assertStatus(404);
    }

    public function test_download_method_success(): void
    {
        $this->actingAs($this->user);
        $attachment = Attachment::factory()->create();
        $response = $this->getJson(route('attachments.download', $attachment->id));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', $attachment->mime_type);
        $response->assertHeader('Content-Disposition', 'attachment; filename=' . $attachment->basename);

        $attachment->forceDelete();
    }

    public function test_download_method_401(): void
    {
        $attachment = Attachment::factory()->create();
        $response = $this->getJson(route('attachments.download', $attachment->id));
        $response->assertStatus(401);
    }

    public function test_download_method_403(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('user');

        $class = Attachment::ATTACHMENT_MODELS[array_rand(Attachment::ATTACHMENT_MODELS)];
        $attachment_id = $class::factory()->create([
            'user_id' => $user->id,
        ])->id;

        /** @var User $user2 */
        $user2 = User::factory()->create();
        $user2->assignRole('user');
        $this->actingAs($user2);

        $response = $this->getJson(route('attachments.download', $attachment_id));
        $response->assertStatus(403);
    }
}
