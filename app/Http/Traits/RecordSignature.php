<?php

namespace App\Http\Traits;

use App\Models\Admin;
use App\Models\Member;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

trait RecordSignature
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authData = self::getAuthData();

            $model->created_at = $model->created_at ?: date("Y-m-d H:i:s");
            $model->created_by = $authData ? (isset($authData->id) ? (string) $authData->id : null) : null;
            $model->updated_at = date("Y-m-d H:i:s");
            $model->updated_by = $authData ? (isset($authData->id) ? (string) $authData->id : null) : null;
        });

        static::updating(function ($model) {
            $authData = self::getAuthData();

            $model->updated_at = date("Y-m-d H:i:s");
            $model->updated_by = $authData ? (isset($authData->id) ? (string) $authData->id : null) : null;
        });

        static::deleting(function ($model) {
            $authData = self::getAuthData();

            $model->deleted_at = date("Y-m-d H:i:s");
            $model->deleted_by = $authData ? (isset($authData->id) ? (string) $authData->id : null) : null;
        });
    }

    public static function getAuthData()
    {
        try {
            $user = (object) JWTAuth::parseToken()->getPayload()->get("user");

            if ($user == null) :
                return null;
            else :
                $user = User::where("email", $user->email)->first();

                return $user;
            endif;
        } catch (Throwable $th) {
            return null;
        }
    }
}
