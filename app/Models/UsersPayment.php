<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersPayment extends Model
{
    use HasFactory, HasUuids;

    protected $table = "users_payments";
    protected $fillable = [
        "status",
        "ticket_id",
        "user_id",
        "expDate"
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, "ticket_id", "id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
