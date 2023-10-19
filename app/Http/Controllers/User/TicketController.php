<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTicketByIdRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService)
    {

    }
    public function get_ticket(): JsonResponse
    {
        $user = Auth::user();
        $response = $this->ticketService->get_ticket($user);
        if($response) {
            return response()->json(["message" => "Билет получен!", "status" => true, "ticket" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_ticket_qr(): JsonResponse
    {
        $user = Auth::user();
        $response = $this->ticketService->get_ticket_qr($user);
        if($response) {
            return response()->json(["message" => "Билет получен!", "status" => true, "qr" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_ticket_by_id(GetTicketByIdRequest $request): JsonResponse
    {
        $response = $this->ticketService->get_ticket_by_id($request->all());
        if($response) {
            return response()->json(["message" => "Билет получен!", "status" => true, "ticket" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
