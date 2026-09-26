<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CbtSession extends Model
{
    protected $fillable = [
        "title", "description", "pic_user_id", "participants_needed",
        "scheduled_start", "scheduled_end", "actual_start", "actual_end", "status",
    ];

    protected $casts = [
        "scheduled_start" => "datetime",
        "scheduled_end"   => "datetime",
        "actual_start"    => "datetime",
        "actual_end"      => "datetime",
    ];

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, "pic_user_id");
    }

    public function pcLogs(): HasMany
    {
        return $this->hasMany(PcSessionLog::class);
    }

    public function computers()
    {
        return $this->belongsToMany(Computer::class, "pc_session_logs", "cbt_session_id", "computer_id")
            ->withPivot(["allocated_at", "released_at", "duration_minutes"])
            ->withTimestamps();
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            "scheduled"  => "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
            "ongoing"    => "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
            "completed"  => "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200",
            "cancelled"  => "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
            default      => "bg-gray-100 text-gray-800",
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            "scheduled" => "Terjadwal",
            "ongoing"   => "Berlangsung",
            "completed" => "Selesai",
            "cancelled" => "Dibatalkan",
            default     => "Unknown",
        };
    }

    public function isOngoing(): bool
    {
        return $this->status === "ongoing";
    }
}