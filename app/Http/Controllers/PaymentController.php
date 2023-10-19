<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyTicketRequeset;
use App\Http\Requests\CallBackRequest;
use App\Models\UsersPayment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {

    }
    public function buy_ticket(): JsonResponse
    {
        $user = Auth::user();
        $response = $this->paymentService->buy_ticket($user);
        if($response) {
            return response()->json(["message" => "Ссылка на оплату получена!", "status" => true, "url" => $response], Response::HTTP_OK);
        } else {
            return response()->json(["message" => "Произошла ошибка!", "status" => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function callback(CallBackRequest $request): Bool
    {
        try {
            $c_f = UsersPayment::where("id", "=", $request->billID)->firstOrFail();
            $rs = Http::withHeader("Authorization","Bearer " . env("QIWI_SECRET_KEY"))
                ->get("https://api.qiwi.com/partner/bill/v1/bills/". $c_f->id);
            DB::transaction(function () use($rs,$c_f){
                if($rs['status']['value'] == "PAID") {
                    $c = $c_f->ticket;
                    $c->is_payed = true;
                    $c->save();
                    $c_f->status = "completed";
                    $c_f->save();
                    return true;
                } else if($rs['status']['value'] != "WAITING") {
                    $c_f->status = "declined";
                }
                $c_f->save();
            });
            return false;
        } catch(Throwable $e) {
            return false;
        }
    }
}
