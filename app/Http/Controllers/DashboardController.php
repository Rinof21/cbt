<?php

namespace App\Http\Controllers;

use App\Models\CbtSession;
use App\Models\ComputerIssue;
use App\Services\WorkloadBalancerService;

class DashboardController extends Controller
{
    public function __construct(private WorkloadBalancerService $balancer) {}

    public function index()
    {
        $summary = $this->balancer->getSummary();

        $ongoingSession = CbtSession::where("status", "ongoing")
            ->with("pic", "pcLogs.computer")
            ->latest()->first();

        $recentIssues = ComputerIssue::with("computer", "reporter")
            ->whereIn("status", ["open", "in_progress"])
            ->latest()->take(5)->get();

        $upcomingSessions = CbtSession::where("status", "scheduled")
            ->with("pic")->orderBy("scheduled_start")->take(5)->get();

        return view("dashboard", compact("summary", "ongoingSession", "recentIssues", "upcomingSessions"));
    }
}