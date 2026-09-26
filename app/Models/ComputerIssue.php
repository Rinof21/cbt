<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComputerIssue extends Model
{
    protected $fillable = [
        "computer_id", "reported_by", "resolved_by", "category",
        "severity", "issue_description", "status", "resolved_at", "resolution_notes",
    ];

    protected $casts = ["resolved_at" => "datetime"];

    public function computer(): BelongsTo
    {
        return $this->belongsTo(Computer::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, "reported_by");
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, "resolved_by");
    }

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            "monitor"     => "Monitor",
            "cpu"         => "Unit PC / CPU",
            "network"     => "Jaringan / LAN",
            "peripherals" => "Periferal",
            "software"    => "Perangkat Lunak / OS",
            default       => "Lainnya",
        };
    }

    public function getSeverityColorClass(): string
    {
        return match($this->severity) {
            "low"      => "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
            "medium"   => "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
            "high"     => "bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200",
            "critical" => "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
            default    => "bg-gray-100 text-gray-800",
        };
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            "open"        => "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
            "in_progress" => "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
            "resolved"    => "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
            default       => "bg-gray-100 text-gray-800",
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            "open"        => "Terbuka",
            "in_progress" => "Dalam Proses",
            "resolved"    => "Selesai",
            default       => "Unknown",
        };
    }
}