<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use App\Models\ComputerIssue;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FloorPlanController extends Controller
{
    public function index()
    {
        return view('floor-plan');
    }

    public function controlSheet(Request $request)
    {
        $data = $this->prepareControlSheetData($request);
        return view('floor-plan.control-sheet', $data);
    }

    public function controlSheetPdf(Request $request)
    {
        $data = $this->prepareControlSheetData($request);
        $pdf = Pdf::loadView('floor-plan.control-sheet-pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('lembar-kontrol-pc-' . now()->format('Ymd-His') . '.pdf');
    }

    private function prepareControlSheetData(Request $request): array
    {
        Carbon::setLocale('id');

        $now = now();
        $customDate = $request->input('date') ? Carbon::parse($request->input('date')) : $now;
        $customTime = $request->input('time', $now->format('H:i'));

        $dayNames = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => "Jum'at",
            'Saturday'  => 'Sabtu',
        ];

        $englishDay = $customDate->format('l');
        $hari = $dayNames[$englishDay] ?? $customDate->isoFormat('dddd');
        $tanggal = $customDate->translatedFormat('d M Y');
        $jam = $customTime . ' WIB';

        $computers = Computer::with(['openIssues' => function ($q) {
            $q->latest();
        }])->orderBy('row_position')->orderBy('col_position')->get();

        // 8 rows x 11 columns grid mapping
        $grid = [];
        for ($r = 1; $r <= 8; $r++) {
            $grid[$r] = [];
            for ($c = 1; $c <= 11; $c++) {
                $grid[$r][$c] = null;
            }
        }

        foreach ($computers as $pc) {
            $r = (int) $pc->row_position;
            $c = (int) $pc->col_position;
            if ($r >= 1 && $r <= 8 && $c >= 1 && $c <= 11) {
                $grid[$r][$c] = $pc;
            }
        }

        // Stats & Inventory Summary
        $totalPcs = $computers->count();
        $availablePcs = $computers->whereIn('status', ['available', 'in_use'])->count();
        $warningPcs = $computers->where('status', 'warning')->count();
        $brokenPcs = $computers->where('status', 'broken')->count();

        // Active issues
        $activeIssues = ComputerIssue::with('computer')
            ->whereIn('status', ['open', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Brand & Monitor Breakdown
        $brands = $computers->groupBy('brand_model')->map->count();
        $monitors = $computers->groupBy('monitor_model')->map->count();

        return compact(
            'grid',
            'hari',
            'tanggal',
            'jam',
            'totalPcs',
            'availablePcs',
            'warningPcs',
            'brokenPcs',
            'activeIssues',
            'brands',
            'monitors',
            'customDate',
            'customTime'
        );
    }
}
