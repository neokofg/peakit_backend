<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\TicketController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("auth")->controller(AuthController::class)->group(function() {
   Route::post("register", "register");
   Route::post("register/approve", "register_approve");
   Route::post("login", "login");
   Route::middleware('auth:sanctum')->post("/register/update", [AuthController::class, "register_update"]);
});

Route::prefix("user")->middleware('auth:sanctum')->group(function () {
    Route::get('get', [UserController::class, "get_user"]);
    Route::get('ticket', [TicketController::class, "get_ticket"]);
    Route::get('ticket/qr', [TicketController::class, "get_ticket_qr"]);
    Route::post('update', [UserController::class, "update_user"]);
    Route::prefix("buy")->group(function() {
       Route::post("ticket", [PaymentController::class, "buy_ticket"]);
    });
});
Route::post("ticket", [TicketController::class, "get_ticket_by_id"]);
Route::get("callback", [PaymentController::class, "callback"]);
