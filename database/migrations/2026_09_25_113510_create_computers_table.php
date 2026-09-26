<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("computers", function (Blueprint $table) {
            $table->id();
            $table->integer("pc_number")->unique();
            $table->integer("row_position");
            $table->integer("col_position");
            $table->string("brand_model", 100)->nullable();
            $table->string("monitor_model", 100)->nullable();
            $table->string("ip_address", 45)->nullable();
            $table->enum("status", ["available", "in_use", "warning", "broken"])->default("available");
            $table->unsignedInteger("total_usage_minutes")->default(0);
            $table->unsignedInteger("total_sessions_count")->default(0);
            $table->text("notes")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("computers");
    }
};