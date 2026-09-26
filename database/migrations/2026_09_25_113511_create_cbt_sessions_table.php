<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("cbt_sessions", function (Blueprint $table) {
            $table->id();
            $table->string("title", 150);
            $table->text("description")->nullable();
            $table->foreignId("pic_user_id")->constrained("users")->onDelete("cascade");
            $table->unsignedInteger("participants_needed")->default(0);
            $table->datetime("scheduled_start");
            $table->datetime("scheduled_end")->nullable();
            $table->datetime("actual_start")->nullable();
            $table->datetime("actual_end")->nullable();
            $table->enum("status", ["scheduled", "ongoing", "completed", "cancelled"])->default("scheduled");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("cbt_sessions");
    }
};