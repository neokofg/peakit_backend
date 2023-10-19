<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->dateTime("expDate");
            $table->enum("status",["declined","pending","completed"])->default("pending");
            $table->foreignUuid("ticket_id")->constrained("tickets")->cascadeOnDelete();
            $table->foreignUuid("user_id")->constrained("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_payments');
    }
};
