<?php

namespace Tests\Feature;

use App\Constant\StatusCodeConstant;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Authenticate a user and return the token.
     *
     * @return string
     */

    private function authenticateUser($user = null)
    {
        if ($user === null) {
            $user = $this->makeUserData();
        }
        $res = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
        ]);

        $data = $res->json()['data'];

        return $data['token'];
    }

    private function makeUserData()
    {
        return User::factory()->create();
    }

    private function makeCategoryData()
    {
        return Category::factory()->create();
    }
    private function makeTaskData()
    {
        return Task::factory()->create();
    }

    public function test_can_create_task()
    {
        $token = $this->authenticateUser();
        $category = $this->makeCategoryData();

        $response = $this->postJson('/api/v1/tasks', [
            'description' => 'Test Task',
            'category_id' => $category->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::CREATED_CODE)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'description',
                    'category_id',
                    'user_id',
                    'category',
                    'user',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('tasks', [
            'description' => 'Test Task',
            'category_id' => $category->id,
        ]);
    }

    public function test_can_get_all_task()
    {
        $user = $this->makeUserData();
        $token = $this->authenticateUser($user);

        Task::factory(3)->create([
            'user_id'     => $user->id,
            'description' => 'Test Task',
        ]);

        $response = $this->getJson("/api/v1/tasks", [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::SUCCESS_CODE)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    [
                        'id',
                        'description',
                        'category_id',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'dev'
            ]);
    }

    public function test_can_get_task()
    {
        $user = $this->makeUserData();
        $token = $this->authenticateUser($user);
        $category = $this->makeCategoryData();

        $task = Task::factory()->create([
            'user_id'     => $user->id,
            'description' => 'Test Task',
            'category_id' => $category->id,
        ]);

        $response = $this->getJson("/api/v1/tasks/{$task->id}", [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::SUCCESS_CODE)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'description',
                    'category_id',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);
    }

    public function test_can_update_task()
    {
        $user = $this->makeUserData();
        $token = $this->authenticateUser($user);
        $category = $this->makeCategoryData();

        $task = Task::factory()->create([
            'user_id'     => $user->id,
            'description' => 'Old Task',
            'category_id' => $category->id,
        ]);

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'description' => 'Updated Task',
            'category_id' => $category->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::SUCCESS_CODE)
            ->assertJsonStructure([
                'status_code',
                'message',
                'data' => [
                    'id',
                    'description',
                    'category_id',
                    'created_at',
                    'updated_at',
                ],
                'dev'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id'          => $task->id,
            'description' => 'Updated Task',
            'category_id' => $category->id,
        ]);
    }

    public function test_can_delete_task()
    {
        $user = $this->makeUserData();
        $token = $this->authenticateUser($user);
        $category = $this->makeCategoryData();

        $task = Task::factory()->create([
            'user_id'     => $user->id,
            'description' => 'Task',
            'category_id' => $category->id,
        ]);

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::SUCCESS_CODE);
    }

    public function test_cannot_create_task_without_description()
    {
        $token = $this->authenticateUser();

        $response = $this->postJson('/api/v1/tasks', [
            'category_id' => 1,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::ERROR_CODE)  // Expecting validation error status
            ->assertJsonStructure([
                'status_code',
                'message',
                'errors'
            ]);
    }

    public function test_cannot_get_non_existent_task()
    {
        $token = $this->authenticateUser();

        $response = $this->getJson('/api/v1/tasks/99999999', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_update_non_existent_task()
    {
        $user = $this->makeUserData();
        $token = $this->authenticateUser($user);
        $category = $this->makeCategoryData();

        $response = $this->putJson('/api/v1/tasks/99999999', [
            'description' => 'Updated Task',
            'category_id' => $category->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_delete_non_existent_task()
    {
        $token = $this->authenticateUser();

        $response = $this->deleteJson('/api/v1/tasks/99999999', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE)  // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_create_task_without_authentication()
    {
        $category = $this->makeCategoryData();

        $response = $this->postJson('/api/v1/tasks', [
            'description' => 'Test Task',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(StatusCodeConstant::UNAUTHORIZED_CODE)  // Expecting unauthorized status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_get_task_without_authentication()
    {
        $task = $this->makeTaskData();

        $response = $this->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(StatusCodeConstant::UNAUTHORIZED_CODE)  // Expecting unauthorized status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_update_task_without_authentication()
    {
        $task = $this->makeTaskData();
        $category = $this->makeCategoryData();

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'description' => 'Updated Task',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(StatusCodeConstant::UNAUTHORIZED_CODE)  // Expecting unauthorized status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_delete_task_without_authentication()
    {
        $task = $this->makeTaskData();

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(StatusCodeConstant::UNAUTHORIZED_CODE)  // Expecting unauthorized status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_get_task_owned_by_another_user()
    {
        $user = User::factory()->create();

        // Create a task for another user
        $task = Task::factory()->create();

        // Attempt to get the task with another user's token
        $response = $this->getJson("/api/v1/tasks/{$task->id}", [
            'Authorization' => "Bearer " . $this->authenticateUser($user),
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE) // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_update_task_owned_by_another_user()
    {
        $category = $this->makeCategoryData();

        $user = User::factory()->create();

        // Create a task for another user
        $task = Task::factory()->create();

        // Attempt to update the task with another user's token
        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'description' => 'Updated Task Description',
            'category_id' => $category->id, // Assuming this category exists
        ], [
            'Authorization' => "Bearer " . $this->authenticateUser($user),
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE) // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }

    public function test_cannot_delete_task_owned_by_another_user()
    {
        $user = User::factory()->create();

        // Create a task for another user
        $task = Task::factory()->create();

        // Attempt to delete the task with another user's token
        $response = $this->deleteJson("/api/v1/tasks/{$task->id}", [], [
            'Authorization' => "Bearer " . $this->authenticateUser($user),
        ]);

        $response->assertStatus(StatusCodeConstant::NOT_FOUND_CODE) // Expecting not found status
            ->assertJsonStructure([
                'status_code',
                'message'
            ]);
    }
}
