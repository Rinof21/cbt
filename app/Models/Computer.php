<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Computer extends Model
{
    protected $fillable = [
        "pc_number", "row_position", "col_position",
        "brand_model", "monitor_model", "ip_address",
        "status", "total_usage_minutes", "total_sessions_count", "notes",
    ];

    public function sessionLogs(): HasMany
    {
        return $this->hasMany(PcSessionLog::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(ComputerIssue::class);
    }

    public function openIssues(): HasMany
    {
        return $this->hasMany(ComputerIssue::class)->whereIn("status", ["open", "in_progress"]);
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            "available", "in_use" => "bg-blue-600 text-white",
            "warning"             => "bg-orange-500 text-white",
            "broken"              => "bg-red-600 text-white",
            default               => "bg-blue-600 text-white",
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            "available", "in_use" => "Siap Digunakan",
            "warning"             => "Rusak Ringan (Bisa Digunakan)",
            "broken"              => "Rusak (Tidak Bisa Digunakan)",
            default               => "Unknown",
        };
    }

    public function getUsageHours(): float
    {
        return round($this->total_usage_minutes / 60, 1);
    }

    public function scopeAvailable($query)
    {
        return $query->where("status", "available");
    }

    public function scopeBroken($query)
    {
        return $query->where("status", "broken");
    }
}