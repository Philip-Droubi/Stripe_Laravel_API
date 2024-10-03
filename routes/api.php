<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Auth Needed
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get("/logout", "logout");
        Route::get("/logout-all", "logoutAllDevices");
    });
    Route::prefix("products")->controller(ProductController::class)->group(function () {
        Route::post("/", "store");
        Route::put("/{id}", "update");
        Route::delete("/{id}", "destroy");
    });
    Route::prefix("orders")->controller(OrderController::class)->group(function () {
        Route::get("/", "index");
        Route::get("/{id}", "show");
        Route::post("/", "store");
    });
});

// No Auth Needed
Route::controller(AuthController::class)->group(function () {
    Route::post("/sign-up", "register");
    Route::post("/login", "login");
});

Route::prefix("products")->controller(ProductController::class)->group(function () {
    Route::get("/", "index");
    Route::get("/{id}", "show");
});
