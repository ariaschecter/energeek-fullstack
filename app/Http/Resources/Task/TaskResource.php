<?php

namespace App\Http\Resources\Task;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'user_id'     => $this->user_id,
            'category'    => new CategoryResource($this->category),
            'user'        => new UserResource($this->user),
        ];
    }
}
