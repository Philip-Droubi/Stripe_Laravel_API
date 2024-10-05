<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout-success', [CheckoutController::class, 'checkoutSuccess']);
Route::get('/checkout-cancel', [CheckoutController::class, 'checkoutCancel']);
Route::post('/webhook', [CheckoutController::class, 'webhook']);
