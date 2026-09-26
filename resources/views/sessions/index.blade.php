@extends('layouts.app')
@section('title', 'Sesi CBT')
@section('subtitle', 'Manajemen sesi ujian komputer berbasis komputer')

@section('content')
<div class="flex justify-end mb-4">
    @can('manage_sessions')
    <a href="{{ route('sessions.create') }}" id="btn-create-session"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Sesi Baru
    </a>
    @endcan
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
    <table class="w-full text-sm datatable">
        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">NO</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sesi</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">PIC</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Mulai</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Peserta</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($sessions as $index => $session)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                <td class="text-center px-4 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ (method_exists($sessions, 'firstItem') && $sessions->firstItem()) ? $sessions->firstItem() + $index : $index + 1 }}</td>
                <td class="px-5 py-4">
                    <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $session->title }}</p>
                    @if($session->description)
                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $session->description }}</p>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $session->getStatusColorClass() }}">
                        {{ $session->getStatusLabel() }}
                    </span>
                </td>
                <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $session->pic->name }}</td>
                <td class="px-5 py-4 text-slate-600 dark:text-slate-300">
                    {{ $session->scheduled_start->format('d M Y') }}<br>
                    <span class="text-xs text-slate-400">{{ $session->scheduled_start->format('H:i') }} WIB</span>
                </td>
                <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $session->participants_needed }} unit</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('sessions.show', $session) }}"
                           class="px-3 py-1.5 text-xs font-medium text-blue-600 border border-blue-300 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                            Detail
                        </a>
                        @can('manage_sessions')
                        <a href="{{ route('sessions.edit', $session) }}"
                           class="px-3 py-1.5 text-xs font-medium text-amber-600 border border-amber-300 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition">
                            Edit
                        </a>
                        @if($session->status === 'ongoing')
                        <form action="{{ route('sessions.end', $session) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Tutup Sesi CBT?', text: 'Sesi {{ $session->title }} akan diakhiri dan alokasi unit PC akan dilepaskan.', confirmButtonText: 'Ya, Tutup Sesi!', confirmButtonColor: '#dc2626' })">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-300 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition cursor-pointer">
                                Tutup
                            </button>
                        </form>
                        @endif
                        @if(in_array($session->status, ['scheduled', 'completed', 'cancelled']))
                        <form action="{{ route('sessions.destroy', $session) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Hapus Sesi CBT?', text: 'Sesi {{ $session->title }} akan dihapus secara permanen.', confirmButtonText: 'Ya, Hapus Sesi!', confirmButtonColor: '#dc2626' })">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-slate-500 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer">
                                Hapus
                            </button>
                        </form>
                        @endif
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Belum ada sesi CBT
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($sessions->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700">
        {{ $sessions->links() }}
    </div>
    @endif
</div>
@endsection
