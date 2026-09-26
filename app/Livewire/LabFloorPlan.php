<?php

namespace App\Livewire;

use App\Models\Computer;
use App\Models\ComputerIssue;
use App\Services\WorkloadBalancerService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LabFloorPlan extends Component
{
    public $computers = [];
    public $selectedPc = null;
    public $showIssueModal = false;
    public $showStatusModal = false;
    public $issueForm = ["category" => "", "severity" => "high", "issue_description" => ""];
    public $newStatus = "";
    public $newRow = 1;
    public $newCol = 1;
    public $recommendedIds = [];

    public $pcIssues = [];
    public $activeSessionInfo = null;
    public $highlightedPcId = null;

    public function mount(): void
    {
        $this->loadComputers();

        $targetPcParam = request()->query('pc') ?? request()->query('pc_number');
        if ($targetPcParam) {
            $pc = Computer::where('id', $targetPcParam)
                ->orWhere('pc_number', $targetPcParam)
                ->first();
            if ($pc) {
                $this->highlightedPcId = $pc->id;
                $this->selectPc($pc->id);
            }
        }
    }

    public function loadComputers(): void
    {
        $this->computers = Computer::withCount('openIssues')->orderBy("pc_number")->get()->toArray();
    }

    public function selectPc($computerId): void
    {
        $computerId = (int) $computerId;
        $pc = Computer::with([
            'sessionLogs' => function ($q) {
                $q->whereNull('released_at')->with('session');
            },
            'issues' => function ($q) {
                $q->whereIn('status', ['open', 'in_progress'])
                  ->with('reporter')
                  ->orderBy('created_at', 'desc');
            }
        ])->find($computerId);

        if (!$pc) {
            return;
        }

        $this->selectedPc = $pc->toArray();
        $this->pcIssues = $pc->issues->toArray();
        $this->newStatus = $pc->status;
        $this->newRow = (int) $pc->row_position;
        $this->newCol = (int) $pc->col_position;
        $this->showStatusModal = false;
        $this->showIssueModal = false;

        $activeLog = $pc->sessionLogs->first();
        if ($activeLog && $activeLog->session) {
            try {
                $sessionLogs = $activeLog->session->pcLogs()->orderBy('id')->pluck('computer_id')->toArray();
                $orderIndex = array_search($pc->id, $sessionLogs);
                $participantOrder = $orderIndex !== false ? ($orderIndex + 1) : null;

                $this->activeSessionInfo = [
                    'session_title' => $activeLog->session->title,
                    'session_id'    => $activeLog->session->id,
                    'order'         => $participantOrder,
                    'start_time'    => $activeLog->allocated_at?->format('H:i'),
                ];
            } catch (\Throwable $e) {
                $this->activeSessionInfo = null;
            }
        } else {
            $this->activeSessionInfo = null;
        }
    }

    public function closeSelection(): void
    {
        $this->selectedPc = null;
        $this->pcIssues = [];
        $this->activeSessionInfo = null;
    }

    public function openIssueModal(int $computerId): void
    {
        $pc = Computer::find($computerId);
        $this->selectedPc = $pc->toArray();
        $this->showIssueModal = true;
        $this->showStatusModal = false;
    }

    public function closeModals(): void
    {
        $this->showIssueModal = false;
        $this->showStatusModal = false;
        $this->issueForm = ["category" => "", "severity" => "high", "issue_description" => ""];
    }

    public function updateLocationAndStatus(): void
    {
        if (!$this->selectedPc) return;

        $targetRow = (int) $this->newRow;
        $targetCol = (int) $this->newCol;

        // Swap positions if another PC occupies the target (row, col)
        if ($targetRow !== $this->selectedPc['row_position'] || $targetCol !== $this->selectedPc['col_position']) {
            $existingPc = Computer::where('row_position', $targetRow)
                ->where('col_position', $targetCol)
                ->where('id', '!=', $this->selectedPc['id'])
                ->first();

            if ($existingPc) {
                $existingPc->update([
                    'row_position' => $this->selectedPc['row_position'],
                    'col_position' => $this->selectedPc['col_position'],
                ]);
            }
        }

        Computer::where('id', $this->selectedPc['id'])->update([
            'row_position' => $targetRow,
            'col_position' => $targetCol,
            'status'       => $this->newStatus,
        ]);

        $this->loadComputers();
        $this->selectPc($this->selectedPc['id']);
        session()->flash('success', "Lokasi Tempat Duduk & status PC #{$this->selectedPc['pc_number']} berhasil diperbarui.");
    }

    public function updateStatus(): void
    {
        if (!in_array($this->newStatus, ["available", "warning", "broken"])) return;

        Computer::where("id", $this->selectedPc["id"])->update(["status" => $this->newStatus]);
        $this->loadComputers();
        $this->closeModals();
        session()->flash("success", "Status PC diperbarui.");
    }

    public function submitIssue(): void
    {
        $this->validate([
            "issueForm.category"          => "required|in:monitor,cpu,network,peripherals,software",
            "issueForm.severity"          => "required|in:low,medium,high,critical",
            "issueForm.issue_description" => "required|string|min:10",
        ]);

        ComputerIssue::create([
            "computer_id"       => $this->selectedPc["id"],
            "reported_by"       => Auth::id(),
            "category"          => $this->issueForm["category"],
            "severity"          => $this->issueForm["severity"],
            "issue_description" => $this->issueForm["issue_description"],
            "status"            => "open",
        ]);

        $newStatus = in_array($this->issueForm["severity"], ["high", "critical"]) ? "broken" : "warning";
        Computer::where("id", $this->selectedPc["id"])->update(["status" => $newStatus]);

        $this->loadComputers();
        $this->closeModals();
        session()->flash("success", "Tiket kerusakan PC berhasil dibuat.");
    }

    public function autoRecommend(int $count = 30): void
    {
        $service = app(WorkloadBalancerService::class);
        $this->recommendedIds = $service->recommend($count)->pluck("id")->toArray();
    }

    public function clearRecommendation(): void
    {
        $this->recommendedIds = [];
    }

    public function getComputerGrid(): array
    {
        $grid = [];
        foreach ($this->computers as $pc) {
            $grid[$pc["row_position"]][$pc["col_position"]] = $pc;
        }
        return $grid;
    }

    public function render()
    {
        $grid = $this->getComputerGrid();
        $totalPcs = count($this->computers);
        $availablePcs = count(array_filter($this->computers, fn($c) => in_array($c['status'], ['available', 'in_use'])));
        $inUsePcs = count(array_filter($this->computers, fn($c) => $c['status'] === 'in_use'));
        $warningPcs = count(array_filter($this->computers, fn($c) => $c['status'] === 'warning'));
        $brokenPcs = count(array_filter($this->computers, fn($c) => $c['status'] === 'broken'));

        return view("livewire.lab-floor-plan", compact("grid", "totalPcs", "availablePcs", "inUsePcs", "warningPcs", "brokenPcs"));
    }
}