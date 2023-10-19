<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailApproveRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterUpdateRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {

    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $response = $this->authService->register($request->all());
        if($response) {
            return response()->json(["message" => "Сообщение успешно отправлено!", "status" => true], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register_approve(EmailApproveRequest $request): JsonResponse
    {
        $response = $this->authService->register_approve($request->all());
        if($response) {
            return response()->json(["message" => "Аккаунт успешно подтвержден!", "status" => true, "token" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register_update(RegisterUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();
        $response = $this->authService->register_update($request->all(), $user);
        if($response) {
            return response()->json(["message" => "Аккаунт успешно создан!", "status" => true], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $response = $this->authService->login($request->all());
        if($response) {
            return response()->json(["message" => "Вход успешно выполнен!", "status" => true, "token" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
