<?php

namespace App\Helpers;

use App\Constant\StatusCodeConstant;
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
                "status"      => false,
                "status_code" => StatusCodeConstant::ERROR_CODE,
                "dev"         => $th->getMessage(),
            ];
        }
    }

    public function showData($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return [
                    "status"      => false,
                    "status_code" => StatusCodeConstant::NOT_FOUND_CODE,
                    "dev"         => null,
                ];
            }

            return [
                "status" => true,
                "data"   => new UserResource($user),
            ];
        } catch (Throwable $th) {
            return [
                "status"      => false,
                "status_code" => StatusCodeConstant::ERROR_CODE,
                "dev"         => $th->getMessage(),
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
                "status"      => false,
                "status_code" => StatusCodeConstant::ERROR_CODE,
                "dev"         => $th->getMessage(),
            ];
        }
    }
    public function updateData(array $payload, string $id)
    {
        try {
            DB::beginTransaction();
            $user = User::find($id);

            if (!$user) {
                return [
                    "status"      => false,
                    "status_code" => StatusCodeConstant::NOT_FOUND_CODE,
                    "dev"         => null,
                ];
            }

            $user->update($payload);

            DB::commit();
            return [
                "status" => true,
                "data"   => new UserResource(User::find($id)),
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status"      => false,
                "status_code" => StatusCodeConstant::ERROR_CODE,
                "dev"         => $th->getMessage(),
            ];
        }
    }

    public function deleteData(string $id)
    {
        try {
            DB::beginTransaction();
            $user = User::find($id);

            if (!$user) {
                return [
                    "status"      => false,
                    "status_code" => StatusCodeConstant::NOT_FOUND_CODE,
                    "dev"         => null,
                ];
            }

            $user = $user->delete();
            DB::commit();
            return [
                "status" => true,
                "data"   => $user,
            ];
        } catch (Throwable $th) {
            DB::rollBack();
            return [
                "status"      => false,
                "status_code" => StatusCodeConstant::ERROR_CODE,
                "dev"         => $th->getMessage(),
            ];
        }
    }
}

