@extends('layouts.app')
@section('title', 'Laporan & Analitik Beban')
@section('subtitle', 'Distribusi penggunaan dan statistik workload lab CBT')

@section('content')
{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 text-center">
        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($summary['total_hours'], 0) }}</p>
        <p class="text-xs text-slate-400 mt-1">Total Jam Lab</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 text-center">
        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $summary['avg_hours_per_pc'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Rata-rata Jam/PC</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 text-center">
        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $sessionStats['completed'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Sesi Selesai</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 text-center">
        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $issueStats['open'] + $issueStats['in_progress'] }}</p>
        <p class="text-xs text-slate-400 mt-1">Tiket Aktif</p>
    </div>
</div>

<div class="flex justify-end mb-4 gap-2">
    @can('export_reports')
    <a href="{{ route('reports.export-pdf') }}" id="btn-export-pdf"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Export PDF
    </a>
    @endcan
</div>

{{-- Heatmap & Table --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    {{-- Heatmap --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-1">Heatmap Beban PC</h3>
        <p class="text-xs text-slate-400 mb-4">Merah = sering dipakai, Biru = jarang dipakai</p>

        @php
        $gridData = collect($heatmap)->groupBy('row_position');
        @endphp

        @for($row = 1; $row <= 8; $row++)
        <div class="flex items-center gap-1 mb-1">
            <span class="text-xs text-slate-400 w-8 text-right shrink-0">R{{ $row }}</span>
            @if(isset($gridData[$row]))
                @foreach($gridData[$row]->sortBy('col_position') as $pc)
                <div class="w-8 h-8 rounded text-center flex items-center justify-center text-xs font-bold text-white transition"
                     style="background: hsl({{ 220 - ($pc['heat_pct'] * 2.2) }}, 80%, {{ 55 - ($pc['heat_pct'] * 0.15) }}%)"
                     title="PC #{{ $pc['pc_number'] }} — {{ $pc['usage_hours'] }} jam / {{ $pc['sessions'] }} sesi">
                    {{ $pc['pc_number'] }}
                </div>
                @endforeach
            @endif
        </div>
        @endfor

        <div class="flex items-center gap-2 mt-4">
            <span class="text-xs text-slate-400">Jarang</span>
            <div class="flex-1 h-3 rounded-full" style="background: linear-gradient(to right, hsl(220,80%,55%), hsl(0,80%,55%))"></div>
            <span class="text-xs text-slate-400">Sering</span>
        </div>
    </div>

    {{-- Top 10 PC beban tertinggi --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="font-bold text-slate-800 dark:text-slate-100 mb-4">Top 10 PC — Jam Tertinggi</h3>
        <div class="space-y-2">
            @foreach($computers->take(10) as $index => $pc)
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400 w-5">{{ $index + 1 }}</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200 w-14">PC #{{ $pc->pc_number }}</span>
                <div class="flex-1 bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                    @php
                    $maxHours = $computers->first() ? $computers->first()->total_usage_minutes : 1;
                    $pct = $maxHours > 0 ? ($pc->total_usage_minutes / $maxHours * 100) : 0;
                    @endphp
                    <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all"
                         style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-xs text-slate-500 dark:text-slate-400 w-16 text-right">{{ round($pc->total_usage_minutes/60, 1) }}j / {{ $pc->total_sessions_count }} sesi</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Full table --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mt-6">
    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-slate-100">Detail Semua PC</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm datatable">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">PC</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Posisi</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Jam</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Sesi</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata/Sesi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($computers as $index => $pc)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="text-center px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-3 font-bold text-slate-800 dark:text-slate-100">PC #{{ $pc->pc_number }}</td>
                    <td class="px-5 py-3 text-slate-500 dark:text-slate-400 text-xs">Baris {{ $pc->row_position }}, Kol {{ $pc->col_position }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs {{ $pc->status === 'available' ? 'text-slate-600' : ($pc->status === 'in_use' ? 'text-green-600' : ($pc->status === 'warning' ? 'text-yellow-600' : 'text-red-600')) }}">
                            {{ $pc->getStatusLabel() }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-medium text-slate-800 dark:text-slate-200">{{ round($pc->total_usage_minutes / 60, 1) }} j</td>
                    <td class="px-5 py-3 text-right text-slate-600 dark:text-slate-300">{{ $pc->total_sessions_count }}</td>
                    <td class="px-5 py-3 text-right text-slate-500 dark:text-slate-400 text-xs">
                        {{ $pc->total_sessions_count > 0 ? round($pc->total_usage_minutes / $pc->total_sessions_count, 0) . ' mnt' : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
