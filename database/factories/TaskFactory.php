<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        return [
            'user_id'     => $user->id,
            'description' => 'Task',
            'category_id' => $category->id,
        ];
    }
}
