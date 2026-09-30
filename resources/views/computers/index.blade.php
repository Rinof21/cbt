@extends('layouts.app')
@section('title', 'Manajemen PC')
@section('subtitle', 'CRUD & kontrol inventaris 88 unit PC lab CBT')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" action="{{ route('computers.index') }}" class="flex items-center gap-2">
        <input type="text" name="search" id="search-pc" value="{{ request('search') }}" placeholder="Cari PC / IP..."
            class="border border-slate-300 dark:border-slate-600 rounded-xl px-4 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 w-48">
        <select name="status" id="filter-pc-status" onchange="this.form.submit()" class="border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <option value="">Semua Status</option>
            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
            <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>Aktif</option>
            <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>Peringatan</option>
            <option value="broken" {{ request('status') === 'broken' ? 'selected' : '' }}>Rusak</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-slate-700 dark:bg-slate-600 text-white text-sm rounded-xl">Cari</button>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">PC</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Posisi</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Brand / Monitor</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">IP Address</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam / Sesi</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($computers as $index => $pc)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                <td class="text-center px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ (method_exists($computers, 'firstItem') && $computers->firstItem()) ? $computers->firstItem() + $index : $index + 1 }}</td>
                <td class="px-5 py-3 font-bold text-slate-800 dark:text-slate-100">PC #{{ $pc->pc_number }}</td>
                <td class="px-5 py-3 text-slate-500 text-xs">B{{ $pc->row_position }}-K{{ $pc->col_position }}</td>
                <td class="px-5 py-3">
                    <p class="text-slate-700 dark:text-slate-300 text-xs">{{ $pc->brand_model ?? '—' }}</p>
                    <p class="text-slate-400 text-xs">{{ $pc->monitor_model ?? '—' }}</p>
                </td>
                <td class="px-5 py-3 text-slate-500 dark:text-slate-400 font-mono text-xs">{{ $pc->ip_address ?? '—' }}</td>
                <td class="px-5 py-3">
                    <form action="{{ route('computers.update-status', $pc) }}" method="POST" class="inline-flex">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                            class="border rounded-lg px-2 py-1 text-xs
                            {{ $pc->status === 'available' ? 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' : '' }}
                            {{ $pc->status === 'in_use' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : '' }}
                            {{ $pc->status === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : '' }}
                            {{ $pc->status === 'broken' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : '' }}
                            border-transparent focus:ring-1 focus:ring-blue-500"
                            {{ $pc->status === 'in_use' ? 'disabled' : '' }}>
                            <option value="available" {{ $pc->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="warning" {{ $pc->status === 'warning' ? 'selected' : '' }}>Peringatan</option>
                            <option value="broken" {{ $pc->status === 'broken' ? 'selected' : '' }}>Rusak</option>
                            @if($pc->status === 'in_use')
                            <option value="in_use" selected>Aktif (Terkunci)</option>
                            @endif
                        </select>
                    </form>
                </td>
                <td class="px-5 py-3 text-right text-xs text-slate-500 dark:text-slate-400">
                    {{ round($pc->total_usage_minutes / 60, 1) }}j / {{ $pc->total_sessions_count }}
                </td>
                <td class="px-5 py-3 text-right">
                    <a href="{{ route('computers.edit', $pc) }}" class="px-3 py-1.5 text-xs text-blue-600 border border-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($computers->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
        {{ $computers->links() }}
    </div>
    @endif
</div>
@endsection
