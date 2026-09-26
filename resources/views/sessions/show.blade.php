@extends('layouts.app')
@section('title', 'Detail Sesi: ' . $session->title)

@section('content')
{{-- Total PC & Allocation Summary Bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total PC Lab</p>
            <p class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $totalPcCount }} <span class="text-xs font-normal text-slate-500">unit</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">PC Dibutuhkan</p>
            <p class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $session->participants_needed }} <span class="text-xs font-normal text-slate-500">unit</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">PC Teralokasi</p>
            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ ($session->status === 'completed' ? $session->pcLogs : $session->pcLogs->whereNull('released_at'))->count() }} <span class="text-xs font-normal text-slate-500">unit</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tersedia Lab</p>
            <p class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $availablePcCount }} <span class="text-xs font-normal text-slate-500">/ {{ $totalPcCount }} PC</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Session Info --}}
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-semibold {{ $session->getStatusColorClass() }}">
                    {{ $session->getStatusLabel() }}
                </span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">{{ $session->title }}</h3>
            @if($session->description)
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $session->description }}</p>
            @endif

            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">PIC</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $session->pic->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Total PC Lab</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $totalPcCount }} unit PC</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">PC Dibutuhkan</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $session->participants_needed }} unit</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">PC Teralokasi</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">{{ ($session->status === 'completed' ? $session->pcLogs : $session->pcLogs->whereNull('released_at'))->count() }} unit</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Jadwal Mulai</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $session->scheduled_start->format('d M Y H:i') }}</span>
                </div>
                @if($session->actual_start)
                <div class="flex justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Mulai Aktual</span>
                    <span class="font-medium text-green-600 dark:text-green-400">{{ $session->actual_start->format('H:i') }} WIB</span>
                </div>
                @endif
                @if($session->actual_end)
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">Selesai Aktual</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">{{ $session->actual_end->format('H:i') }} WIB</span>
                </div>
                @endif
            </div>

            @can('manage_sessions')
            <div class="mt-5 space-y-2.5">
                <a href="{{ route('sessions.edit', $session) }}" id="btn-edit-session"
                    class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition shadow flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Detail Sesi
                </a>
                @if($session->status === 'ongoing')
                <form action="{{ route('sessions.end', $session) }}" method="POST" onsubmit="return confirmForm(event, { title: 'Tutup Sesi CBT?', text: 'Sesi {{ $session->title }} akan diakhiri dan jam terbang unit PC akan dihitung.', confirmButtonText: 'Ya, Tutup Sesi!', confirmButtonColor: '#dc2626', icon: 'warning' })">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition shadow flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>🔴 Tutup Sesi</span>
                    </button>
                </form>
                @endif
            </div>
            @endcan
        </div>
    </div>

    {{-- PC Allocation Interactive Grid --}}
    <div class="lg:col-span-2 space-y-6">
        @php
            // Build grid array for 8 rows x 11 columns
            $grid = [];
            foreach ($allComputers as $pcItem) {
                $grid[$pcItem->row_position][$pcItem->col_position] = $pcItem;
            }
            $recommendedIds = $recommended->pluck('id')->toArray();

            // Map active allocated logs (filter out released logs for ongoing/scheduled sessions)
            $activePcLogs = ($session->status === 'completed')
                ? $session->pcLogs
                : $session->pcLogs->whereNull('released_at');

            $allocatedPcIds = $activePcLogs->pluck('computer_id')->toArray();
            $allocatedLogs = [];
            foreach ($activePcLogs as $idx => $log) {
                $allocatedLogs[$log->computer_id] = [
                    'order' => $idx + 1,
                    'log'   => $log,
                ];
            }
        @endphp

        {{-- Form for Scheduled or Ongoing Session --}}
        @if(in_array($session->status, ['scheduled', 'ongoing']))
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        <span>Denah Tempat Duduk Sesi (8 Baris × 11 Kolom)</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Mode Edit Alokasi</span>
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Klik tempat duduk untuk memilih/batal memilih PC. Klik tombol <span class="font-bold text-blue-600">R1-R8</span> untuk memilih semua PC per baris.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span id="selected-counter-badge" class="px-3 py-1 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-xs">
                        {{ count($allocatedPcIds) }} / {{ $session->participants_needed }} PC Dipilih
                    </span>
                </div>
            </div>

            @can('manage_sessions')
            @php
                $formRoute = ($session->status === 'scheduled') ? route('sessions.start', $session) : route('sessions.update-allocation', $session);
            @endphp
            <form action="{{ $formRoute }}" method="POST" id="start-session-form">
                @csrf

                {{-- Toolbar Actions --}}
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4 bg-slate-50 dark:bg-slate-700/50 p-3 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="autoSelectRecommended()"
                            class="px-3 py-1.5 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-xs">
                            ⚡ Auto-Pilih {{ $session->participants_needed }} PC Rekomendasi
                        </button>
                        <button type="button" onclick="selectAllAvailable()"
                            class="px-3 py-1.5 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-xs">
                            Pilih Berurutan (1-{{ $session->participants_needed }})
                        </button>
                    </div>
                    <button type="button" onclick="clearSeatSelections()"
                        class="px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:text-red-600 transition">
                        🧹 Reset Pilihan
                    </button>
                </div>

                {{-- Interactive 8x11 R1-R8 Seating Grid --}}
                <div class="overflow-x-auto p-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700 my-4">
                    <div class="w-full max-w-[480px] mx-auto py-2" style="display: flex; flex-direction: column; gap: 10px;">
                        {{-- Pintu Masuk (Entrance Header Bar) --}}
                        <div class="flex items-center gap-3">
                            <span class="w-7 shrink-0"></span>
                            <div class="flex-1 py-2 px-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl text-center text-xs font-extrabold tracking-wider shadow-sm flex items-center justify-center gap-2">
                                <span>🚪 PINTU MASUK LAB CBT 🚪</span>
                            </div>
                        </div>

                        @for($row = 1; $row <= 8; $row++)
                        <div class="flex items-center gap-3">
                            {{-- Clickable Row Label for Select All per Row --}}
                            <button type="button" onclick="toggleRowSelection({{ $row }})"
                                title="Klik untuk Pilih / Batal Pilih Semua PC di Baris R{{ $row }}"
                                class="text-xs font-bold py-1 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-blue-600 hover:text-white text-slate-600 dark:text-slate-300 transition-colors w-7 text-center shrink-0 cursor-pointer">
                                R{{ $row }}
                            </button>
                            <div style="display: grid; grid-template-columns: repeat(11, minmax(0, 1fr)); gap: 10px;" class="flex-1">
                                @for($col = 1; $col <= 11; $col++)
                                    @if(isset($grid[$row][$col]))
                                    @php
                                        $pc = $grid[$row][$col];
                                        $isRecommended = in_array($pc->id, $recommendedIds);
                                        $isCurrentlyAllocated = in_array($pc->id, $allocatedPcIds);
                                        $isChecked = ($session->status === 'scheduled') ? $isRecommended : $isCurrentlyAllocated;
                                        $isBroken = ($pc->status === 'broken');
                                        $isWarning = ($pc->status === 'warning');
                                    @endphp
                                    <div class="block" style="aspect-ratio: 1 / 1;">
                                        <input type="checkbox" name="computer_ids[]" value="{{ $pc->id }}"
                                            data-pc-number="{{ $pc->pc_number }}"
                                            data-row="{{ $row }}" data-col="{{ $col }}"
                                            data-is-recommended="{{ $isRecommended ? '1' : '0' }}"
                                            data-is-broken="{{ $isBroken ? '1' : '0' }}"
                                            data-is-warning="{{ $isWarning ? '1' : '0' }}"
                                            class="seat-checkbox sr-only"
                                            {{ $isBroken ? 'disabled' : ($isChecked ? 'checked' : '') }}>
                                        <div id="seat-box-{{ $pc->id }}"
                                            onclick="handleSeatClick({{ $pc->id }}, {{ $isBroken ? 'true' : 'false' }}, {{ $pc->pc_number }})"
                                            title="{{ $isBroken ? 'PC #' . $pc->pc_number . ' Rusak — Tidak Dapat Digunakan' : ($isWarning ? 'PC #' . $pc->pc_number . ' Rusak Ringan — Bisa Digunakan' : 'PC #' . $pc->pc_number . ' Siap Digunakan') }}"
                                            class="w-full h-full rounded-lg text-xs sm:text-sm font-bold flex items-center justify-center transition-all duration-150 shadow-xs select-none seat-btn relative
                                                {{ $isBroken ? 'bg-red-600 text-white cursor-not-allowed opacity-90' : ($isChecked ? 'bg-blue-600 text-white shadow-sm cursor-pointer' : ($isWarning ? 'bg-orange-500 hover:bg-blue-600 text-white cursor-pointer' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white cursor-pointer')) }}">
                                            {{ $pc->pc_number }}
                                            @if($isBroken) <span class="text-[7px] absolute top-0.5 right-0.5">✕</span> @endif
                                            @if($isWarning && !$isChecked) <span class="text-[7px] absolute top-0.5 right-0.5">⚡</span> @endif
                                        </div>
                                    </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                @if($session->status === 'scheduled')
                <button type="submit" id="btn-start-session"
                    class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <span class="text-base">🟢 Mulai Sesi Sekarang</span>
                    <span id="btn-submit-pc-count" class="text-xs bg-green-700 px-2.5 py-1 rounded-lg text-green-100 font-extrabold">({{ $session->participants_needed }} PC)</span>
                </button>
                @else
                <button type="submit" id="btn-update-allocation"
                    class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <span class="text-base">💾 Simpan Perubahan Alokasi Tempat Duduk</span>
                    <span id="btn-submit-pc-count" class="text-xs bg-blue-700 px-2.5 py-1 rounded-lg text-blue-100 font-extrabold">({{ count($allocatedPcIds) }} PC)</span>
                </button>
                @endif
            </form>
            @endcan
        </div>
        @endif

        {{-- Completed Session Read-Only Grid --}}
        @if($session->status === 'completed')
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                <div>
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-100">
                        Riwayat Alokasi Tempat Duduk Sesi (8 Baris × 11 Kolom)
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Klik tombol tempat duduk untuk melihat detail riwayat alokasi PC.
                    </p>
                </div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-xl text-xs font-bold shrink-0">
                    <span>{{ $session->pcLogs->count() }} / {{ $totalPcCount }} PC Digunakan</span>
                </div>
            </div>

            {{-- 8x11 Grid --}}
            <div class="overflow-x-auto p-5 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="w-full max-w-[480px] mx-auto py-2" style="display: flex; flex-direction: column; gap: 10px;">
                    {{-- Pintu Masuk (Entrance Header Bar) --}}
                    <div class="flex items-center gap-3">
                        <span class="w-7 shrink-0"></span>
                        <div class="flex-1 py-2 px-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl text-center text-xs font-extrabold tracking-wider shadow-sm flex items-center justify-center gap-2">
                            <span>🚪 PINTU MASUK LAB CBT 🚪</span>
                        </div>
                    </div>

                    @for($row = 1; $row <= 8; $row++)
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 w-7 text-center shrink-0">R{{ $row }}</span>
                        <div style="display: grid; grid-template-columns: repeat(11, minmax(0, 1fr)); gap: 10px;" class="flex-1">
                            @for($col = 1; $col <= 11; $col++)
                                @if(isset($grid[$row][$col]))
                                @php
                                    $pc = $grid[$row][$col];
                                    $isAllocated = isset($allocatedLogs[$pc->id]);
                                    $allocData = $isAllocated ? $allocatedLogs[$pc->id] : null;
                                    $isBroken = ($pc->status === 'broken');
                                @endphp
                                <button type="button" style="aspect-ratio: 1 / 1;"
                                    @if($isBroken)
                                        onclick="alert('PC #{{ $pc->pc_number }} Rusak — Tidak Dapat Digunakan');"
                                    @else
                                        onclick="openParticipantModal({{ $isAllocated ? $allocData['order'] : 'null' }}, {{ json_encode($pc) }}, '{{ $isAllocated ? ($allocData['log']->duration_minutes ? $allocData['log']->duration_minutes . ' menit' : 'Selesai') : 'Tidak Digunakan' }}')"
                                    @endif
                                    title="PC #{{ $pc->pc_number }} | Baris R{{ $row }}, Kolom {{ $col }} {{ $isBroken ? '| Rusak' : ($isAllocated ? '| Urutan Peserta #' . $allocData['order'] : '') }}"
                                    class="w-full h-full rounded-lg text-xs sm:text-sm font-bold transition-all duration-150 shadow-xs flex items-center justify-center select-none
                                        {{ $isBroken ? 'bg-red-600 text-white cursor-not-allowed opacity-90' : 'cursor-pointer hover:scale-105 active:scale-95' }}
                                        {{ $isAllocated && !$isBroken ? 'bg-blue-600 text-white shadow-sm' : '' }}
                                        {{ !$isAllocated && !$isBroken ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' : '' }}
                                    ">
                                    {{ $pc->pc_number }}
                                </button>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Participant Order & PC Location Modal --}}
<div id="participant-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6 relative overflow-hidden" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
            <div class="flex items-center gap-2">
                <span id="modal-order-badge" class="px-3 py-1 bg-blue-600 text-white text-xs font-black rounded-xl uppercase tracking-wider">
                    Peserta #1
                </span>
                <h3 id="modal-title" class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    Detail Tempat Duduk & PC
                </h3>
            </div>
            <button onclick="closeParticipantModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xl font-bold p-1">✕</button>
        </div>

        {{-- Location highlight banner --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-4 text-white mb-4 flex items-center justify-between shadow-sm">
            <div>
                <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Letak Denah Tempat Duduk</p>
                <p id="modal-location-text" class="text-xl font-extrabold mt-0.5">Baris R1, Kolom 1</p>
                <p id="modal-pc-number" class="text-xs text-blue-200">Nomor Unit: PC #1</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl shadow-inner">
                📍
            </div>
        </div>

        {{-- Spec details --}}
        <div class="space-y-2 text-xs mb-5">
            <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                <span class="text-slate-500">Status Dalam Sesi</span>
                <span id="modal-duration-status" class="font-bold text-green-600 dark:text-green-400">Aktif Dalam Sesi</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                <span class="text-slate-500">Brand / Model PC</span>
                <span id="modal-brand" class="font-medium text-slate-800 dark:text-slate-200">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                <span class="text-slate-500">Monitor</span>
                <span id="modal-monitor" class="font-medium text-slate-800 dark:text-slate-200">-</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                <span class="text-slate-500">IP Address</span>
                <span id="modal-ip" class="font-mono text-slate-800 dark:text-slate-200">-</span>
            </div>
            <div class="flex justify-between py-1.5">
                <span class="text-slate-500">Total Jam Terbang PC</span>
                <span id="modal-usage" class="font-medium text-slate-800 dark:text-slate-200">-</span>
            </div>
        </div>

        <div class="flex gap-3">
            <a id="modal-floorplan-link" href="#"
                class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow flex items-center justify-center gap-1.5">
                <span>🗺️ Lihat di Denah Lab Utama</span>
            </a>
            <button type="button" onclick="closeParticipantModal()"
                class="px-4 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-xl hover:bg-slate-300 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleSeatClick(pcId, isBroken, pcNumber) {
    if (isBroken) {
        alert(`PC #${pcNumber} Rusak — Tidak Dapat Digunakan`);
        return;
    }
    const checkbox = document.querySelector(`.seat-checkbox[value="${pcId}"]`);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateSelectedCounter();
    }
}

function updateSelectedCounter() {
    const checkboxes = document.querySelectorAll('.seat-checkbox');
    const checked = document.querySelectorAll('.seat-checkbox:checked');

    checkboxes.forEach(cb => {
        const pcId = cb.value;
        const box = document.getElementById(`seat-box-${pcId}`);
        const isBroken = cb.dataset.isBroken === '1';

        if (box) {
            const isWarning = cb.dataset.isWarning === '1';
            if (isBroken) {
                box.className = 'w-full h-full rounded-xl text-xs sm:text-base font-bold flex items-center justify-center select-none seat-btn bg-red-600 text-white cursor-not-allowed opacity-90 relative';
            } else if (cb.checked) {
                box.className = 'w-full h-full rounded-xl text-xs sm:text-base font-bold flex items-center justify-center transition-all duration-150 shadow-sm cursor-pointer select-none seat-btn bg-blue-600 text-white relative';
            } else if (isWarning) {
                box.className = 'w-full h-full rounded-xl text-xs sm:text-base font-bold flex items-center justify-center transition-all duration-150 shadow-xs cursor-pointer select-none seat-btn bg-orange-500 hover:bg-blue-600 text-white relative';
            } else {
                box.className = 'w-full h-full rounded-xl text-xs sm:text-base font-bold flex items-center justify-center transition-all duration-150 shadow-xs cursor-pointer select-none seat-btn bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white relative';
            }
        }
    });

    const counterBadge = document.getElementById('selected-counter-badge');
    const submitBtnCount = document.getElementById('btn-submit-pc-count');
    if (counterBadge) {
        counterBadge.innerText = `${checked.length} / {{ $session->participants_needed }} PC Dipilih`;
    }
    if (submitBtnCount) {
        submitBtnCount.innerText = `(${checked.length} PC)`;
    }
}

function toggleRowSelection(rowNumber) {
    const rowCheckboxes = Array.from(document.querySelectorAll(`.seat-checkbox[data-row="${rowNumber}"]`))
        .filter(cb => !cb.disabled && cb.dataset.isBroken !== '1');

    if (rowCheckboxes.length === 0) return;

    const allChecked = rowCheckboxes.every(cb => cb.checked);
    rowCheckboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
    updateSelectedCounter();
}

function autoSelectRecommended() {
    document.querySelectorAll('.seat-checkbox').forEach(cb => {
        if (!cb.disabled && cb.dataset.isBroken !== '1') {
            cb.checked = (cb.dataset.isRecommended === '1');
        }
    });
    updateSelectedCounter();
}

function selectAllAvailable() {
    const needed = parseInt("{{ $session->participants_needed }}");
    const checkboxes = Array.from(document.querySelectorAll('.seat-checkbox'))
        .filter(cb => !cb.disabled && cb.dataset.isBroken !== '1');

    checkboxes.forEach((cb, index) => {
        cb.checked = index < needed;
    });
    updateSelectedCounter();
}

function clearSeatSelections() {
    document.querySelectorAll('.seat-checkbox').forEach(cb => {
        if (!cb.disabled) cb.checked = false;
    });
    updateSelectedCounter();
}

// Initial counter update
document.addEventListener('DOMContentLoaded', updateSelectedCounter);
updateSelectedCounter();

function openParticipantModal(orderIndex, pcData, sessionDuration = 'Aktif') {
    if (pcData.status === 'broken') {
        alert(`PC #${pcData.pc_number} Rusak — Tidak Dapat Digunakan`);
        return;
    }

    document.getElementById('modal-order-badge').innerText = orderIndex ? `Urutan Peserta #${orderIndex}` : 'Posisi Tempat Duduk';
    document.getElementById('modal-title').innerText = `Detail PC #${pcData.pc_number}`;
    document.getElementById('modal-location-text').innerText = `Baris R${pcData.row_position}, Kolom ${pcData.col_position}`;
    document.getElementById('modal-pc-number').innerText = `Nomor Tempat Duduk: PC #${pcData.pc_number}`;
    document.getElementById('modal-duration-status').innerText = sessionDuration;
    document.getElementById('modal-brand').innerText = pcData.brand_model || 'Standard CBT Workstation';
    document.getElementById('modal-monitor').innerText = pcData.monitor_model || 'Standard LED Monitor';
    document.getElementById('modal-ip').innerText = pcData.ip_address || '192.168.1.' + pcData.pc_number;
    document.getElementById('modal-usage').innerText = (pcData.total_usage_minutes ? (pcData.total_usage_minutes / 60).toFixed(1) : 0) + ' jam (' + (pcData.total_sessions_count || 0) + ' sesi)';

    document.getElementById('modal-floorplan-link').href = `{{ route('floor-plan') }}?pc=${pcData.id}`;

    const modal = document.getElementById('participant-modal');
    modal.classList.remove('hidden');
}

function closeParticipantModal() {
    const modal = document.getElementById('participant-modal');
    modal.classList.add('hidden');
}

document.getElementById('participant-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeParticipantModal();
    }
});
</script>
@endpush
@endsection
