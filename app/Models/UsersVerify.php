<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersVerify extends Model
{
    use HasFactory, HasUuids;

    protected $table = "users_verify";

    //  The attributes that are mass assignable.
    protected $fillable = [
        "code",
        "user_id"
    ];


    // returns User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", 'id');
    }
}
