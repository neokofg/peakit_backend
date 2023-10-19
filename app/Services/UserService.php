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
}
