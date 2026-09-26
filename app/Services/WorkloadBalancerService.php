<?php

namespace App\Services;

use App\Models\Computer;

class WorkloadBalancerService
{
    public function recommend(int $count): \Illuminate\Database\Eloquent\Collection
    {
        return Computer::where("status", "available")
            ->orderBy("total_usage_minutes", "asc")
            ->orderBy("total_sessions_count", "asc")
            ->orderBy("pc_number", "asc")
            ->limit($count)
            ->get();
    }

    public function getHeatmapData(): array
    {
        $computers = Computer::orderBy("pc_number")->get();
        $maxMinutes = $computers->max("total_usage_minutes") ?: 1;

        return $computers->map(function ($pc) use ($maxMinutes) {
            return [
                "id"           => $pc->id,
                "pc_number"    => $pc->pc_number,
                "row_position" => $pc->row_position,
                "col_position" => $pc->col_position,
                "status"       => $pc->status,
                "usage_minutes"=> $pc->total_usage_minutes,
                "usage_hours"  => round($pc->total_usage_minutes / 60, 1),
                "sessions"     => $pc->total_sessions_count,
                "heat_pct"     => round(($pc->total_usage_minutes / $maxMinutes) * 100),
            ];
        })->toArray();
    }

    public function getSummary(): array
    {
        $computers = Computer::all();
        $totalMinutes = $computers->sum("total_usage_minutes");

        return [
            "total_pcs"        => $computers->count(),
            "available"        => $computers->where("status", "available")->count(),
            "in_use"           => $computers->where("status", "in_use")->count(),
            "warning"          => $computers->where("status", "warning")->count(),
            "broken"           => $computers->where("status", "broken")->count(),
            "total_hours"      => round($totalMinutes / 60, 1),
            "avg_hours_per_pc" => $computers->count() > 0
                                    ? round(($totalMinutes / $computers->count()) / 60, 1)
                                    : 0,
            "most_used"        => $computers->sortByDesc("total_usage_minutes")->first(),
            "least_used"       => $computers->where("status", "available")->sortBy("total_usage_minutes")->first(),
        ];
    }
}