@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan status lab CBT hari ini')

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tersedia</p>
            <div class="w-9 h-9 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center">
                <div class="w-3 h-3 rounded-full bg-slate-400"></div>
            </div>
        </div>
        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $summary['available'] }}</p>
        <p class="text-xs text-slate-400 mt-1">unit siap pakai</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aktif</p>
            <div class="w-9 h-9 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $summary['in_use'] }}</p>
        <p class="text-xs text-slate-400 mt-1">sedang digunakan</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Peringatan</p>
            <div class="w-9 h-9 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
            </div>
        </div>
        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $summary['warning'] }}</p>
        <p class="text-xs text-slate-400 mt-1">gangguan minor</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Rusak</p>
            <div class="w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
            </div>
        </div>
        <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $summary['broken'] }}</p>
        <p class="text-xs text-slate-400 mt-1">tidak dapat digunakan</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Ongoing Session --}}
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Sesi Aktif</h3>
            <a href="{{ route('sessions.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Lihat semua →</a>
        </div>
        @if($ongoingSession)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xs font-medium text-green-700 dark:text-green-400 uppercase tracking-wide">BERLANGSUNG</span>
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-100 text-lg">{{ $ongoingSession->title }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">PIC: {{ $ongoingSession->pic->name }}</p>
                    <p class="text-sm text-slate-500 mt-1">{{ $ongoingSession->pcLogs->count() }} PC dialokasikan</p>
                    <p class="text-xs text-slate-400 mt-1">Mulai: {{ $ongoingSession->actual_start?->format('H:i') }} WIB</p>
                </div>
                @can('manage_sessions')
                <form action="{{ route('sessions.end', $ongoingSession) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Tutup Sesi CBT?', text: 'Sesi {{ $ongoingSession->title }} akan diakhiri dan alokasi unit PC akan dilepaskan.', confirmButtonText: 'Ya, Tutup Sesi!', confirmButtonColor: '#dc2626', icon: 'warning' })">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition shadow-md whitespace-nowrap flex items-center gap-1.5 cursor-pointer">
                        <span>🔴 Tutup Sesi</span>
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="w-14 h-14 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada sesi yang sedang berlangsung</p>
            @can('manage_sessions')
            <a href="{{ route('sessions.create') }}" class="mt-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                + Buat Sesi Baru
            </a>
            @endcan
        </div>
        @endif

        {{-- Upcoming Sessions --}}
        @if($upcomingSessions->count() > 0)
        <div class="mt-5">
            <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Sesi Terjadwal</h4>
            <div class="space-y-2">
                @foreach($upcomingSessions as $session)
                <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $session->title }}</p>
                        <p class="text-xs text-slate-400">{{ $session->scheduled_start->format('d M Y, H:i') }}</p>
                    </div>
                    <a href="{{ route('sessions.show', $session) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Recent Issues --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Tiket Aktif</h3>
            <a href="{{ route('issues.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Semua →</a>
        </div>
        @if($recentIssues->count() > 0)
        <div class="space-y-3">
            @foreach($recentIssues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="block p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $issue->getSeverityColorClass() }}">
                        {{ ucfirst($issue->severity) }}
                    </span>
                    <span class="text-xs text-slate-400">PC #{{ $issue->computer->pc_number }}</span>
                </div>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $issue->getCategoryLabel() }}</p>
                <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $issue->issue_description }}</p>
            </a>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada tiket kerusakan aktif</p>
        </div>
        @endif
    </div>
</div>

{{-- Stats Bar --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg">
        <p class="text-blue-100 text-xs font-semibold uppercase tracking-wider mb-2">Total Jam Terbang Lab</p>
        <p class="text-4xl font-bold">{{ number_format($summary['total_hours'], 0) }}</p>
        <p class="text-blue-200 text-sm mt-1">jam akumulasi semua PC</p>
    </div>
    <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-2xl p-5 text-white shadow-lg">
        <p class="text-slate-300 text-xs font-semibold uppercase tracking-wider mb-2">Rata-rata per PC</p>
        <p class="text-4xl font-bold">{{ $summary['avg_hours_per_pc'] }}</p>
        <p class="text-slate-400 text-sm mt-1">jam / unit</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg">
        <p class="text-emerald-100 text-xs font-semibold uppercase tracking-wider mb-2">Total Unit PC</p>
        <p class="text-4xl font-bold">{{ $summary['total_pcs'] }}</p>
        <p class="text-emerald-200 text-sm mt-1">unit terdaftar dalam sistem</p>
    </div>
</div>
@endsection
