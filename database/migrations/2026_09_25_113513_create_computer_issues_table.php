<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("computer_issues", function (Blueprint $table) {
            $table->id();
            $table->foreignId("computer_id")->constrained("computers")->onDelete("cascade");
            $table->foreignId("reported_by")->constrained("users")->onDelete("cascade");
            $table->foreignId("resolved_by")->nullable()->constrained("users")->onDelete("set null");
            $table->enum("category", ["monitor", "cpu", "network", "peripherals", "software"]);
            $table->enum("severity", ["low", "medium", "high", "critical"])->default("high");
            $table->text("issue_description");
            $table->enum("status", ["open", "in_progress", "resolved"])->default("open");
            $table->datetime("resolved_at")->nullable();
            $table->text("resolution_notes")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("computer_issues");
    }
};