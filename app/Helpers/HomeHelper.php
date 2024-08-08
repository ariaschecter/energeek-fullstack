<?php

namespace App\Helpers;

use App\Http\Resources\Task\TaskCollection;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class HomeHelper
{
    public function storeTasks(array $payload)
    {
        try {

            DB::beginTransaction();
            $tasks = $payload['tasks'];

            $user = User::createUser(name: $payload['name'], username: $payload['username'], email: $payload['email']);

            $tasks = collect($tasks)->map(function ($task) use ($user) {
                $task['user_id'] = $user->id;
                return $task;
            })->reject(fn ($task) => !$task['description'])->values()->toArray();

            $tasks = Task::insert($tasks);

            $tasks = Task::where('user_id', $user->id)->get();
            DB::commit();
            return [
                "status_code" => 201,
                "status"      => true,
                "data"        => new TaskCollection($tasks),
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

