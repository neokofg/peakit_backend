<?php

namespace App\Console\Commands;

use App\Models\UsersPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $c_fs = UsersPayment::where("status", '=', "pending")->get();
        foreach($c_fs as $c_f) {
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
        }
    }
}
