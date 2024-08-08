<?php

namespace App\Helpers;

use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class TaskHelper
{
    public function getAllData()
    {
        try {
            $user = auth('api')->user();
            $users = Task::where('user_id', $user->id)->get();

            return [
                "status" => true,
                "data"   => new TaskCollection($users),
            ];
        } catch (Throwable $th) {
            return [
                "status" => false,
                "dev"    => $th->getMessage(),
            ];
        }
    }

    public function showData($id)
    {
        try {
            $user = auth('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);

            return [
                "status" => true,
                "data"   => new TaskResource($task),
            ];
        } catch (Throwable $th) {
            return [
                "status" => false,
                "dev"    => $th->getMessage(),
            ];
        }
    }

    public function storeData(array $payload)
    {
        try {
            DB::beginTransaction();
            $user = auth('api')->user();

            $payload['user_id'] = $user->id;
            $task = Task::create($payload);

            DB::commit();
            return [
                "status" => true,
                "data"   => new TaskResource($task),
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status" => false,
                "dev"    => $th->getMessage(),
            ];
        }
    }
    public function updateData(array $payload, string $id)
    {
        try {
            DB::beginTransaction();
            $user = auth('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);

            $task->update($payload);

            DB::commit();
            return [
                "status" => true,
                "data"   => new TaskResource(Task::find($id)),
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status" => false,
                "dev"    => $th->getMessage(),
            ];
        }
    }

    public function deleteData(string $id)
    {
        try {
            DB::beginTransaction();
            $user = auth('api')->user();
            $task = Task::where('user_id', $user->id)->findOrFail($id);

            $task = $task->delete();

            DB::commit();
            return [
                "status" => true,
                "data"   => $task,
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status" => false,
                "dev"    => $th->getMessage(),
            ];
        }
    }
}

