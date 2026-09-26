<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("pc_session_logs", function (Blueprint $table) {
            $table->id();
            $table->foreignId("cbt_session_id")->constrained("cbt_sessions")->onDelete("cascade");
            $table->foreignId("computer_id")->constrained("computers")->onDelete("cascade");
            $table->datetime("allocated_at");
            $table->datetime("released_at")->nullable();
            $table->unsignedInteger("duration_minutes")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("pc_session_logs");
    }
};