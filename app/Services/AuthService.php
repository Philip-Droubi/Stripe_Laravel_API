<?php

namespace App\Services;

use App\Models\User;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService.
 */
class AuthService extends MainService
{
    public function register($validatedData)
    {
        DB::beginTransaction();
        $user = User::create([
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "password" => Hash::make($validatedData["password"]),
        ]);
        $token = $user->createToken('access_token');
        $data["token"] = $token->plainTextToken;
        $data["user_id"] = $user->id;
        DB::commit();
        return $data;
    }

    public function login($user)
    {
        DB::beginTransaction();
        $token = $user->createToken('access_token');
        $data["token"] = $token->plainTextToken;
        $data["user_id"] = $user->id;
        DB::commit();
        return ["data" => $data];
    }

    public function logout($user)
    {
        DB::beginTransaction();
        $user->currentAccessToken()->delete();
        DB::commit();
        return true;
    }

    public function logoutAllDevices($user)
    {
        DB::beginTransaction();
        $user->tokens()->delete();
        DB::commit();
        return true;
    }
}
