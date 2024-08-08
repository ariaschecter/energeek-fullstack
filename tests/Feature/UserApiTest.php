<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $response = $this->postJson('/api/v1/users', [
            'name'     => 'Test User',
            'username' => 'username123',
            'email'    => 'testuser@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'Test User',
            'email' => 'testuser@example.com'
        ]);
    }

    public function test_can_get_user()
    {
        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'testuser@example.com',
        ]);

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'username',
                    'email',
                    'created_at',
                ],
                'dev'
            ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create([
            'name'     => 'Old User',
            'username' => 'oldusername',
            'email'    => 'olduser@example.com',
        ]);

        $response = $this->putJson("/api/v1/users/{$user->id}", [
            'name'     => 'Updated User',
            'username' => 'newusername',
            'email'    => 'updateduser@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Updated User',
            'email' => 'updateduser@example.com',
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'testuser@example.com',
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200);
    }

    public function test_cannot_create_user_with_missing_fields()
    {
        // Missing 'name' field
        $response = $this->postJson('/api/v1/users', [
            'email'    => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)  // Expecting validation error status
            ->assertJsonStructure([
                'status_code',
                'message',
                'errors'
            ]);
    }

    public function test_cannot_get_non_existent_user()
    {
        // Try to retrieve a user that does not exist
        $response = $this->getJson('/api/v1/users/99999999');

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_update_non_existent_user()
    {
        // Try to update a user that does not exist
        $response = $this->putJson('/api/v1/users/99999999', [
            'name'     => 'Updated User',
            'username' => 'randomusername',
            'email'    => 'updateduser@example.com'
        ]);

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_delete_non_existent_user()
    {
        // Try to delete a user that does not exist
        $response = $this->deleteJson('/api/v1/users/99999999');

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }
}
