<?php

namespace App\Services;

use App\Models\User;
use App\Models\UsersVerify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AuthService {

    private function generateCode(): Int
    {
        $is_unique = false;
        while($is_unique == false){
            $code = rand(1000,9999);
            if(!UsersVerify::where('code',$code)->exists()){
                $is_unique = true;
            }
        }
        return $code;
    }

    public function register($r): Bool
    {
        try {
            //generating code
            $c = $this->generateCode();

            $e = $r['email'];

            DB::transaction(function () use($c, $e) {
                //creating User model
                $u = User::where("is_email_verified", "=", false)->firstOrCreate(["email" => $e]);
                if(!$u){
                    return false;
                }
                //creating Verify model
                $u_v = UsersVerify::firstOrNew(['user_id' => $u->id]);
                $u_v->code = $c;
                $u_v->save();

                //sending mail
                Mail::send('emails.email_verification', ['code' => $c], function($message) use($e){
                    $message->to($e);
                    $message->subject('CharitySteps');
                });
            });
            return true;
        } catch(Throwable $e) {
            return false;
        }

    }
}
