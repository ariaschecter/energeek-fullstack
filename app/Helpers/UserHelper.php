<?php

namespace App\Helpers;

use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserHelper
{
    public function getAllData()
    {
        try {
            $users = User::orderBy('name', 'ASC')->get();

            return [
                "status" => true,
                "data"   => new UserCollection($users),
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
            $users = User::findOrFail($id);

            return [
                "status" => true,
                "data"   => new UserResource($users),
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
            $user = User::createUser(name: $payload['name'], username: $payload['username'], email: $payload['email']);
            DB::commit();
            return [
                "status" => true,
                "data"   => new UserResource($user),
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
            $user = User::findOrFail($id);

            $user->update($payload);

            DB::commit();
            return [
                "status" => true,
                "data"   => new UserResource(User::find($id)),
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
            $user = User::findOrFail($id);
            $user = $user->delete();
            DB::commit();
            return [
                "status" => true,
                "data"   => $user,
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

