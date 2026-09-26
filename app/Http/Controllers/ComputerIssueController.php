<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\ComputerIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComputerIssueController extends Controller
{
    public function index(Request $request)
    {
        $query = ComputerIssue::with("computer", "reporter")->latest();

        if ($request->filled("status")) $query->where("status", $request->status);
        if ($request->filled("category")) $query->where("category", $request->category);
        if ($request->filled("severity")) $query->where("severity", $request->severity);

        $issues = $query->paginate(15)->withQueryString();
        return view("issues.index", compact("issues"));
    }

    public function create(Request $request)
    {
        $computer = $request->filled("computer_id") ? Computer::findOrFail($request->computer_id) : null;
        $computers = Computer::orderBy("pc_number")->get();
        return view("issues.create", compact("computer", "computers"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "computer_id"       => "required|exists:computers,id",
            "category"          => "required|in:monitor,cpu,network,peripherals,software",
            "severity"          => "required|in:low,medium,high,critical",
            "issue_description" => "required|string|min:10",
        ]);

        $issue = ComputerIssue::create(array_merge($validated, [
            "reported_by" => Auth::id(),
            "status"      => "open",
        ]));

        $newStatus = in_array($validated["severity"], ["high", "critical"]) ? "broken" : "warning";
        Computer::where("id", $validated["computer_id"])->update(["status" => $newStatus]);

        return redirect()->route("issues.show", $issue)->with("success", "Tiket kerusakan berhasil dibuat.");
    }

    public function show(ComputerIssue $issue)
    {
        $issue->load("computer", "reporter", "resolver");
        return view("issues.show", compact("issue"));
    }

    public function update(Request $request, ComputerIssue $issue)
    {
        $validated = $request->validate([
            "status"           => "required|in:open,in_progress,resolved",
            "resolution_notes" => "nullable|string",
        ]);

        if ($validated["status"] === "resolved") {
            $validated["resolved_at"] = now();
            $validated["resolved_by"] = Auth::id();
            Computer::where("id", $issue->computer_id)
                ->whereIn("status", ["broken", "warning"])
                ->update(["status" => "available"]);
        }

        $issue->update($validated);
        return redirect()->route("issues.show", $issue)->with("success", "Status tiket berhasil diperbarui.");
    }
}