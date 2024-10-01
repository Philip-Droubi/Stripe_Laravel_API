<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(AuthRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->authService->register($validatedData);
        return $this->success($data, "Welcome " . $validatedData["name"]);
    }

    public function login(AuthRequest $request)
    {
        $validatedData = $request->validated();
        if (
            Auth::attempt(['email' => $validatedData["email"], 'password' => $validatedData["password"]])
        ) {
            $user = $request->user();
            $data = $this->authService->login($user);
            return $this->success($data["data"], "Welcome Back!");
        }
        return $this->fail("Invalid credentials");
    }

    public function logout(Request $request)
    {
        $data = $this->authService->logout($request->user());
        return $this->success(null, "logged out");
    }

    public function logoutAllDevices(Request $request)
    {
        $data = $this->authService->logoutAllDevices($request->user());
        return $this->success(null, 'logged out');
    }
}
