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
    public function register($validatedData): array
    {
        DB::beginTransaction();
        $user = User::create([
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "password" => Hash::make($validatedData["password"]),
        ]);
        $data = [
            "token" => $user->createToken('access_token')->plainTextToken,
            "user_id" => $user->id
        ];
        DB::commit();
        return $data;
    }

    public function login($user): array
    {
        DB::beginTransaction();
        $data = [
            "token" => $user->createToken('access_token')->plainTextToken,
            "user_id" => $user->id
        ];
        DB::commit();
        return ["data" => $data];
    }

    public function logout($user): bool
    {
        DB::beginTransaction();
        $user->currentAccessToken()->delete();
        DB::commit();
        return true;
    }

    public function logoutAllDevices($user): bool
    {
        DB::beginTransaction();
        $user->tokens()->delete();
        DB::commit();
        return true;
    }
}
