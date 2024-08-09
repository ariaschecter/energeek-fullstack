<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $response = $this->postJson('/api/v1/categories', [
            'name' => 'Test Category',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }

    public function test_can_get_category()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
        ]);

        $response = $this->getJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'Old Category',
        ]);

        $response = $this->putJson("/api/v1/categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
        ]);

        $response = $this->deleteJson("/api/v1/categories/{$category->id}");

        $response->assertStatus(200);
    }

    public function test_cannot_create_category_with_missing_name()
    {
        // Missing 'name' field
        $response = $this->postJson('/api/v1/categories', []);

        $response->assertStatus(422)  // Expecting validation error status
            ->assertJsonStructure([
                'status_code',
                'message',
                'errors'
            ]);
    }

    public function test_cannot_get_non_existent_category()
    {
        // Try to retrieve a category that does not exist
        $response = $this->getJson('/api/v1/categories/99999999');

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_update_non_existent_category()
    {
        // Try to update a category that does not exist
        $response = $this->putJson('/api/v1/categories/99999999', [
            'name' => 'Updated Category'
        ]);

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_delete_non_existent_category()
    {
        // Try to delete a category that does not exist
        $response = $this->deleteJson('/api/v1/categories/99999999');

        $response->assertStatus(404)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }
}
