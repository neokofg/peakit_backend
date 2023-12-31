<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {

    }

    public function get_user(): JsonResponse
    {
        $user = Auth::user();
        $response = $this->userService->get_user($user);
        if($response) {
            return response()->json(["message" => "Пользователь получен!", "status" => true, "user" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update_user(UserUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $response = $this->userService->user_update($request->validated(), $user);
        if($response) {
            return response()->json(["message" => "Аккаунт успешно обновлен!", "status" => true], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
