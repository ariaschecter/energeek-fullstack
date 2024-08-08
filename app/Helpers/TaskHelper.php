<?php

namespace App\Helpers;

use App\Http\Resources\Task\TaskCollection;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class TaskHelper
{
    public function storeTasks(array $payload)
    {
        try {

            DB::beginTransaction();
            $tasks = $payload['tasks'];

            $user = User::createUser($payload);

            $tasks = collect($tasks)->map(function ($task) use ($user) {
                $task['user_id'] = $user->id;
                return $task;
            })->toArray();

            $tasks = Task::insert($tasks);

            DB::commit();
            return [
                "status_code" => 201,
                "status"      => true,
                "data"        => $tasks,
                "message"     => "Berhasil menambahkan data"
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status"  => false,
                "message" => "Gagal menambahkan data",
                "dev"     => $th->getMessage(),
            ];
        }
    }
}

