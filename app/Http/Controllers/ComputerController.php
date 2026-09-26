<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $query = Computer::query();

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }
        if ($request->filled("search")) {
            $q = $request->search;
            $query->where(fn($qb) => $qb->where("pc_number", "like", "%$q%")
                ->orWhere("ip_address", "like", "%$q%")
                ->orWhere("brand_model", "like", "%$q%"));
        }

        $computers = $query->orderBy("pc_number")->paginate(20)->withQueryString();
        return view("computers.index", compact("computers"));
    }

    public function edit(Computer $computer)
    {
        return view("computers.edit", compact("computer"));
    }

    public function update(Request $request, Computer $computer)
    {
        $validated = $request->validate([
            "brand_model"   => "nullable|string|max:100",
            "monitor_model" => "nullable|string|max:100",
            "ip_address"    => "nullable|string|max:45",
            "notes"         => "nullable|string",
        ]);

        $computer->update($validated);
        return redirect()->route("computers.index")->with("success", "PC #" . $computer->pc_number . " berhasil diperbarui.");
    }

    public function updateStatus(Request $request, Computer $computer)
    {
        $validated = $request->validate([
            "status" => "required|in:available,warning,broken",
        ]);

        if ($computer->status === "in_use") {
            return back()->with("error", "PC sedang digunakan dalam sesi aktif.");
        }

        $computer->update($validated);
        return back()->with("success", "Status PC #" . $computer->pc_number . " diperbarui.");
    }
}