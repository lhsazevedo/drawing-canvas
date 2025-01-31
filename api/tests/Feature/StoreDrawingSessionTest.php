<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreDrawingSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_creante_drawing_session(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->postJson('/drawing-sessions', [
                'name' => 'My Drawing Session',
                'description' => 'This is a test drawing session',
                'is_public' => true,
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('drawing_sessions', [
            'id' => $response->json('data.id'),
            'user_id' => $response->json('data.user_id'),
            'name' => 'My Drawing Session',
            'description' => 'This is a test drawing session',
            'is_public' => true,
        ]);
    }

    public function test_guest_can_create_drawing_session(): void
    {
        $response = $this->postJson('/drawing-sessions', [
            'name' => 'My Drawing Session',
            'description' => 'This is a test drawing session',
            'is_public' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('drawing_sessions', [
            'id' => $response->json('data.id'),
            'user_id' => null,
            'name' => 'My Drawing Session',
            'description' => 'This is a test drawing session',
            'is_public' => true,
        ]);
    }

    public function test_some_fields_are_required(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/drawing-sessions', [
            'description' => 'This is a test drawing session',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'is_public']);

        $this->assertDatabaseEmpty('drawing_sessions');
    }

    public function test_description_is_optional(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/drawing-sessions', [
            'name' => 'My Drawing Session',
            'is_public' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('drawing_sessions', [
            'name' => 'My Drawing Session',
            'description' => null,
            'is_public' => true,
        ]);
    }
}
