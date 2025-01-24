<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreDrawingSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_new_drawing_session(): void
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->postJson('/drawing-sessions', [
            'name' => 'My Drawing Session',
            'public_id' => 'my-drawing-session',
            'description' => 'This is a test drawing session',
            'is_public' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('drawing_sessions', [
                'id' => $response->json('data.id'),
                'user_id' => $response->json('data.user_id'),
                'name' => 'My Drawing Session',
                'public_id' => 'my-drawing-session',
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
            ->assertJsonValidationErrors(['name', 'public_id', 'is_public']);

        $this->assertDatabaseEmpty('drawing_sessions');
    }

    public function test_description_is_optional(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->postJson('/drawing-sessions', [
            'name' => 'My Drawing Session',
            'public_id' => 'my-drawing-session',
            'is_public' => true,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('drawing_sessions', [
            'name' => 'My Drawing Session',
            'public_id' => 'my-drawing-session',
            'description' => null,
            'is_public' => true,
        ]);
    }
}
