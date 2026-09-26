<?php

namespace App\Http\Controllers;

use App\Models\CbtSession;
use App\Models\Computer;
use App\Models\PcSessionLog;
use App\Services\WorkloadBalancerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CbtSessionController extends Controller
{
    public function __construct(private WorkloadBalancerService $balancer) {}

    public function index()
    {
        $sessions = CbtSession::with("pic")->latest()->paginate(15);
        return view("sessions.index", compact("sessions"));
    }

    public function create()
    {
        $availableCount = Computer::where("status", "available")->count();
        return view("sessions.create", compact("availableCount"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "title"               => "required|string|max:150",
            "description"         => "nullable|string",
            "participants_needed" => "required|integer|min:1|max:88",
            "scheduled_start"     => "required|date",
            "scheduled_end"       => "nullable|date|after:scheduled_start",
        ]);

        $session = CbtSession::create(array_merge($validated, [
            "pic_user_id" => Auth::id(),
            "status"      => "scheduled",
        ]));

        return redirect()->route("sessions.show", $session)->with("success", "Sesi CBT berhasil dibuat.");
    }

    public function edit(CbtSession $session)
    {
        $availableCount = Computer::where("status", "available")->count();
        return view("sessions.edit", compact("session", "availableCount"));
    }

    public function update(Request $request, CbtSession $session)
    {
        $validated = $request->validate([
            "title"               => "required|string|max:150",
            "description"         => "nullable|string",
            "participants_needed" => "required|integer|min:1|max:88",
            "scheduled_start"     => "required|date",
            "scheduled_end"       => "nullable|date|after:scheduled_start",
            "status"              => "nullable|string|in:scheduled,ongoing,completed,cancelled",
        ]);

        $session->update($validated);

        return redirect()->route("sessions.show", $session)->with("success", "Detail Sesi CBT berhasil diperbarui.");
    }

    public function show(CbtSession $session)
    {
        $session->load("pic", "pcLogs.computer");

        $allComputers = Computer::orderBy("pc_number")->get();
        $totalPcCount = $allComputers->count();
        $availablePcCount = $allComputers->whereIn("status", ["available", "in_use"])->count();
        $inUsePcCount = $allComputers->where("status", "in_use")->count();
        $warningPcCount = $allComputers->where("status", "warning")->count();
        $brokenPcCount = $allComputers->where("status", "broken")->count();

        $recommended = $this->balancer->recommend($session->participants_needed);

        return view("sessions.show", compact(
            "session",
            "recommended",
            "allComputers",
            "totalPcCount",
            "availablePcCount",
            "inUsePcCount",
            "warningPcCount",
            "brokenPcCount"
        ));
    }

    public function start(Request $request, CbtSession $session)
    {
        if ($session->status !== "scheduled") {
            return back()->with("error", "Sesi tidak dapat dimulai.");
        }

        $request->validate([
            "computer_ids"   => "required|array|min:1",
            "computer_ids.*" => "exists:computers,id",
        ]);

        DB::transaction(function () use ($session, $request) {
            $now = now();
            $session->update(["status" => "ongoing", "actual_start" => $now]);

            foreach ($request->computer_ids as $computerId) {
                PcSessionLog::create([
                    "cbt_session_id" => $session->id,
                    "computer_id"    => $computerId,
                    "allocated_at"   => $now,
                ]);
                Computer::where("id", $computerId)->update(["status" => "in_use"]);
            }
        });

        return redirect()->route("sessions.show", $session)
            ->with("success", "Sesi dimulai! " . count($request->computer_ids) . " PC dialokasikan.");
    }

    public function end(CbtSession $session)
    {
        if ($session->status !== "ongoing") {
            return back()->with("error", "Sesi tidak sedang berlangsung.");
        }

        DB::transaction(function () use ($session) {
            $now = now();
            $session->update(["status" => "completed", "actual_end" => $now]);

            $logs = PcSessionLog::where("cbt_session_id", $session->id)->whereNull("released_at")->get();

            foreach ($logs as $log) {
                $minutes = (int) $log->allocated_at->diffInMinutes($now);
                $log->update(["released_at" => $now, "duration_minutes" => $minutes]);
                Computer::where("id", $log->computer_id)->increment("total_usage_minutes", $minutes);
                Computer::where("id", $log->computer_id)->increment("total_sessions_count");
                Computer::where("id", $log->computer_id)->where("status", "in_use")->update(["status" => "available"]);
            }
        });

        return redirect()->route("sessions.index")->with("success", "Sesi ditutup. Jam terbang PC diperbarui.");
    }

    public function updateAllocation(Request $request, CbtSession $session)
    {
        $request->validate([
            "computer_ids"   => "nullable|array",
            "computer_ids.*" => "exists:computers,id",
        ]);

        $newComputerIds = $request->input("computer_ids", []);

        DB::transaction(function () use ($session, $newComputerIds) {
            $now = now();
            $currentLogs = PcSessionLog::where("cbt_session_id", $session->id)
                ->whereNull("released_at")
                ->get();

            $currentPcIds = $currentLogs->pluck("computer_id")->toArray();

            // PCs to release (were in currentPcIds, but not in newComputerIds)
            $pcsToRelease = array_diff($currentPcIds, $newComputerIds);
            foreach ($pcsToRelease as $pcId) {
                $log = PcSessionLog::where("cbt_session_id", $session->id)
                    ->where("computer_id", $pcId)
                    ->whereNull("released_at")
                    ->first();
                if ($log) {
                    $minutes = (int) $log->allocated_at->diffInMinutes($now);
                    if ($minutes < 1) {
                        $log->delete();
                    } else {
                        $log->update(["released_at" => $now, "duration_minutes" => $minutes]);
                        Computer::where("id", $pcId)->increment("total_usage_minutes", $minutes);
                        Computer::where("id", $pcId)->increment("total_sessions_count");
                    }
                    Computer::where("id", $pcId)->where("status", "in_use")->update(["status" => "available"]);
                }
            }

            // PCs to add (are in newComputerIds, but not in currentPcIds)
            $pcsToAdd = array_diff($newComputerIds, $currentPcIds);
            foreach ($pcsToAdd as $pcId) {
                PcSessionLog::create([
                    "cbt_session_id" => $session->id,
                    "computer_id"    => $pcId,
                    "allocated_at"   => $now,
                ]);
                Computer::where("id", $pcId)->update(["status" => "in_use"]);
            }
        });

        return redirect()->route("sessions.show", $session)
            ->with("success", "Alokasi tempat duduk PC berhasil diperbarui (" . count($newComputerIds) . " PC).");
    }

    public function destroy(CbtSession $session)
    {
        if ($session->status === "ongoing") {
            return back()->with("error", "Tidak dapat menghapus sesi yang sedang berlangsung.");
        }
        $session->delete();
        return redirect()->route("sessions.index")->with("success", "Sesi dihapus.");
    }
}