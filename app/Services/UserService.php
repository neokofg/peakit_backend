<?php

namespace App\Services;

use App\Models\User;
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
            $u->update($r);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
