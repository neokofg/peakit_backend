<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\UsersPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Qiwi\Api\BillPayments;
use Throwable;

class PaymentService {

    public function buy_ticket($u): mixed
    {
        if($u->tickets()->where("is_payed",'=',true)->count() > 0) {
            return false;
        }
        try {
            $b_p = new BillPayments(env("QIWI_SECRET_KEY"));
            $expDate = Carbon::now()->addMinutes(15)->toIso8601String();

            $url = DB::transaction(function () use($expDate,$b_p,$u) {
                $t = new Ticket();
                $t->user_id = $u->id;
                $t->save();

                $c_f = new UsersPayment();
                $c_f->expDate = $expDate;
                $c_f->ticket_id = $t->id;
                $c_f->user_id = $u->id;
                $c_f->save();

                $f = [
                    'amount' => 10,
                    'currency' => 'RUB',
                    'comment' => 'Покупка билета на Международные соревнования Дети Азии 2024',
                    'expirationDateTime' => $expDate,
                    'email' => 'support@charity-steps.ru',
                    'account' => $u->id,
                    'successUrl' => 'http://localhost:8000/api/callback?billID='. $c_f->id
                ];
                $rsp = $b_p->createBill($c_f->id, $f);
                return $rsp['payUrl'];
            });
            return $url;
        } catch (Throwable $e) {
            return dd($e);
        }
    }

}
