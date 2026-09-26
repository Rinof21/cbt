<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\CbtSession;
use App\Models\ComputerIssue;
use App\Services\WorkloadBalancerService;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkloadReportController extends Controller
{
    public function __construct(private WorkloadBalancerService $balancer) {}

    public function index()
    {
        $computers   = Computer::orderBy("total_usage_minutes", "desc")->get();
        $summary     = $this->balancer->getSummary();
        $heatmap     = $this->balancer->getHeatmapData();
        $issueStats  = [
            "total"       => ComputerIssue::count(),
            "open"        => ComputerIssue::where("status", "open")->count(),
            "in_progress" => ComputerIssue::where("status", "in_progress")->count(),
            "resolved"    => ComputerIssue::where("status", "resolved")->count(),
        ];
        $sessionStats = [
            "total"     => CbtSession::count(),
            "ongoing"   => CbtSession::where("status", "ongoing")->count(),
            "completed" => CbtSession::where("status", "completed")->count(),
        ];

        return view("reports.index", compact("computers", "summary", "heatmap", "issueStats", "sessionStats"));
    }

    public function exportPdf()
    {
        $computers   = Computer::orderBy("total_usage_minutes", "desc")->get();
        $summary     = $this->balancer->getSummary();
        $generatedAt = now()->format("d/m/Y H:i");

        $pdf = Pdf::loadView("reports.pdf", compact("computers", "summary", "generatedAt"))
            ->setPaper("a4", "landscape");

        return $pdf->download("laporan-beban-pc-" . now()->format("Ymd-His") . ".pdf");
    }
}