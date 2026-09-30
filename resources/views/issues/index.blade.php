@extends('layouts.app')
@section('title', 'Tiket Kerusakan')
@section('subtitle', 'Log dan manajemen perbaikan PC lab CBT')

@section('content')
<div class="flex items-center justify-between mb-4">
    {{-- Filters --}}
    <form method="GET" action="{{ route('issues.index') }}" class="flex items-center gap-2">
        <select name="status" id="filter-status" onchange="this.form.submit()" class="border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <option value="">Semua Status</option>
            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Terbuka</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
        </select>
        <select name="category" id="filter-category" onchange="this.form.submit()" class="border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <option value="">Semua Kategori</option>
            <option value="monitor" {{ request('category') === 'monitor' ? 'selected' : '' }}>Monitor</option>
            <option value="cpu" {{ request('category') === 'cpu' ? 'selected' : '' }}>Unit PC / CPU</option>
            <option value="network" {{ request('category') === 'network' ? 'selected' : '' }}>Jaringan / LAN</option>
            <option value="peripherals" {{ request('category') === 'peripherals' ? 'selected' : '' }}>Periferal</option>
            <option value="software" {{ request('category') === 'software' ? 'selected' : '' }}>Perangkat Lunak</option>
        </select>
    </form>
    @can('create_issue')
    <a href="{{ route('issues.create') }}" id="btn-create-issue"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Tiket
    </a>
    @endcan
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">PC</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keparahan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dilaporkan</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($issues as $index => $issue)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                <td class="text-center px-4 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ (method_exists($issues, 'firstItem') && $issues->firstItem()) ? $issues->firstItem() + $index : $index + 1 }}</td>
                <td class="px-5 py-4">
                    <span class="font-bold text-slate-800 dark:text-slate-100">PC #{{ $issue->computer->pc_number }}</span>
                    <p class="text-xs text-slate-400">Baris {{ $issue->computer->row_position }}</p>
                </td>
                <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $issue->getCategoryLabel() }}</td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $issue->getSeverityColorClass() }}">
                        {{ ucfirst($issue->severity) }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $issue->getStatusColorClass() }}">
                        {{ $issue->getStatusLabel() }}
                    </span>
                </td>
                <td class="px-5 py-4 text-slate-500 dark:text-slate-400 text-xs">{{ $issue->reporter->name }}</td>
                <td class="px-5 py-4 text-slate-500 dark:text-slate-400 text-xs">{{ $issue->created_at->format('d M Y H:i') }}</td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('issues.show', $issue) }}" class="px-3 py-1.5 text-xs text-blue-600 border border-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada tiket kerusakan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($issues->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
        {{ $issues->links() }}
    </div>
    @endif
</div>
@endsection
