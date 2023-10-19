<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService {
    public function get_user($u): mixed
    {
        try {
            return User::where("id","=",$u->id)->get();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function user_update($r, $u): Bool
    {
        try {
            if(isset($r['name'])) {
                $u->name = $r['name'];
            }
            if(isset($r['password'])) {
                $u->password = Hash::make($r['password']);
            }
            $u->save();
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
