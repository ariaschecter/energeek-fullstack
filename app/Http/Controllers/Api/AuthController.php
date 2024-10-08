<?php

namespace App\Http\Controllers\Api;

use App\Constant\StatusCodeConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) :
            return response()->failed(error: $request->validator->errors());
        endif;

        $validated = $request->validated();


        $user = User::where('email', $validated['email'])->first(['id', 'name', 'username', 'email']);

        if (!$user) {
            return response()->failed(
                message: 'User not found',
                httpCode: StatusCodeConstant::NOT_FOUND_CODE
            );
        }

        $token = auth('api')->login($user);


        $user = Auth::user();

        $data = [
            'name'     => $user->name,
            'email'    => $user->email,
            'username' => $user->email,
            'token'    => $token,
        ];

        return response()->success(message: 'Berhasil login', data: $data);
    }

    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'email'    => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $user = User::create([
    //         'name'     => $request->name,
    //         'email'    => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         'user'    => $user
    //     ]);
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     return response()->json([
    //         'message' => 'Successfully logged out',
    //     ]);
    // }

    // public function refresh()
    // {
    //     return response()->json([
    //         'user'          => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type'  => 'bearer',
    //         ]
    //     ]);
    // }
}
