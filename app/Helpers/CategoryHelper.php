<?php

namespace App\Helpers;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryHelper
{
    public function getAllData()
    {
        try {
            $users = Category::all();

            return [
                "status" => true,
                "data"   => new CategoryCollection($users),
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
            $users = Category::findOrFail($id);

            return [
                "status" => true,
                "data"   => new CategoryResource($users),
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
            $category = Category::create($payload);
            DB::commit();
            return [
                "status" => true,
                "data"   => new CategoryResource($category),
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
            $category = Category::findOrFail($id);

            $category->update($payload);

            DB::commit();
            return [
                "status" => true,
                "data"   => new CategoryResource(Category::find($id)),
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
            $category = Category::findOrFail($id);
            $category = $category->delete();
            DB::commit();
            return [
                "status" => true,
                "data"   => $category,
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

