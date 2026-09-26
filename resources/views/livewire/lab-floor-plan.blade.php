<div>
    {{-- Total PC & Summary Stats Header --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 mb-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-2.5 sm:p-3 shadow-xs border border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-semibold text-slate-500">Total PC Lab</span>
            <span class="text-base sm:text-lg font-bold text-slate-800 dark:text-slate-100">{{ $totalPcs }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-2.5 sm:p-3 shadow-xs border border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-semibold text-blue-600">Siap Digunakan</span>
            <span class="text-base sm:text-lg font-bold text-blue-600 dark:text-blue-400">{{ $availablePcs }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-2.5 sm:p-3 shadow-xs border border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-semibold text-orange-600">Rusak Ringan</span>
            <span class="text-base sm:text-lg font-bold text-orange-600">{{ $warningPcs }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-2.5 sm:p-3 shadow-xs border border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <span class="text-[11px] sm:text-xs font-semibold text-red-600">Rusak Berat</span>
            <span class="text-base sm:text-lg font-bold text-red-600">{{ $brokenPcs }}</span>
        </div>
    </div>

    {{-- Toolbar & Legend --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-3 sm:p-4 mb-4">
        <div class="flex flex-wrap items-center gap-3 sm:gap-6">
            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-3 sm:gap-5 flex-1">
                <span class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mr-1">Keterangan:</span>
                <div class="inline-flex items-center gap-1.5">
                    <span style="display: inline-block; width: 12px; height: 12px; min-width: 12px; min-height: 12px; border-radius: 50%; background-color: #2563eb;"></span>
                    <span class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 font-semibold">Siap Digunakan</span>
                </div>
                <div class="inline-flex items-center gap-1.5">
                    <span style="display: inline-block; width: 12px; height: 12px; min-width: 12px; min-height: 12px; border-radius: 50%; background-color: #f97316;"></span>
                    <span class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 font-semibold">Rusak Ringan</span>
                </div>
                <div class="inline-flex items-center gap-1.5">
                    <span style="display: inline-block; width: 12px; height: 12px; min-width: 12px; min-height: 12px; border-radius: 50%; background-color: #dc2626;"></span>
                    <span class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 font-semibold">Rusak Berat</span>
                </div>
                <div class="inline-flex items-center gap-1.5 border-l border-slate-200 dark:border-slate-700 pl-3">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-amber-400 text-slate-900 text-[9px] font-extrabold shadow-xs">🎫</span>
                    <span class="text-[11px] sm:text-xs text-slate-700 dark:text-slate-300 font-semibold">Ada Tiket Aktif</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Layout (Left: Grid, Right: Detail Panel) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 items-start">
        {{-- Left: Compact Floor Plan Grid --}}
        <div class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-3 sm:p-5">
            <div class="mb-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200">Denah Lab CBT (8×11)</span>
                </div>
                <span class="text-[10px] sm:text-[11px] text-slate-400">Klik PC untuk detail →</span>
            </div>

            <div class="overflow-x-auto p-2 sm:p-4 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="w-full max-w-[440px] mx-auto py-1" style="display: flex; flex-direction: column; gap: 8px;">
                    {{-- Pintu Masuk (Entrance Header Bar) --}}
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-6 shrink-0"></span>
                        <div class="flex-1 py-1.5 px-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl text-center text-xs font-extrabold tracking-wider shadow-sm flex items-center justify-center gap-2">
                            <span>🚪 PINTU MASUK LAB CBT 🚪</span>
                        </div>
                    </div>

                    @for($row = 1; $row <= 8; $row++)
                    <div class="flex items-center gap-2" wire:key="row-{{ $row }}">
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 w-6 text-center shrink-0">R{{ $row }}</span>
                        <div style="display: grid; grid-template-columns: repeat(11, minmax(0, 1fr)); gap: 6px;" class="flex-1">
                            @for($col = 1; $col <= 11; $col++)
                                @if(isset($grid[$row][$col]))
                                @php
                                    $pc = $grid[$row][$col];
                                    $isSelected = ($selectedPc && $selectedPc['id'] == $pc['id']);
                                    $isHighlighted = ($highlightedPcId && $highlightedPcId == $pc['id']);
                                    $hasTicket = (($pc['open_issues_count'] ?? 0) > 0);
                                    $statusLabel = match($pc['status']) {
                                        'broken'  => 'Rusak (Tidak Bisa Digunakan)',
                                        'warning' => 'Rusak Ringan (Bisa Digunakan)',
                                        default   => 'Siap Digunakan',
                                    };
                                @endphp
                                <button type="button" style="aspect-ratio: 1 / 1;"
                                    wire:key="pc-btn-{{ $pc['id'] }}"
                                    wire:click="selectPc({{ $pc['id'] }})"
                                    title="PC #{{ $pc['pc_number'] }} | Baris R{{ $row }}, Kolom {{ $col }} | {{ $statusLabel }} {{ $hasTicket ? '| Ada ' . $pc['open_issues_count'] . ' Tiket Aktif' : '' }}"
                                    class="w-full h-full rounded-lg text-xs font-bold transition-all duration-150 shadow-xs relative flex items-center justify-center cursor-pointer hover:scale-105 active:scale-95 text-white select-none
                                        {{ $isSelected ? 'ring-4 ring-yellow-400 ring-offset-2 scale-110 z-20 shadow-lg' : '' }}
                                        {{ $isHighlighted && !$isSelected ? 'ring-4 ring-yellow-400 ring-offset-1 scale-105 z-10 animate-bounce' : '' }}
                                        {{ in_array($pc['status'], ['available', 'in_use']) ? 'bg-blue-600 hover:bg-blue-700' : '' }}
                                        {{ $pc['status'] === 'warning' ? 'bg-orange-500 hover:bg-orange-600' : '' }}
                                        {{ $pc['status'] === 'broken' ? 'bg-red-600 hover:bg-red-700 opacity-90' : '' }}
                                    ">
                                    {{ $pc['pc_number'] }}

                                    {{-- Active Ticket Badge Indicator --}}
                                    @if($hasTicket)
                                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-amber-400 text-slate-900 text-[8px] font-black flex items-center justify-center shadow-md ring-2 ring-white dark:ring-slate-800 animate-bounce pointer-events-none z-10" title="Ada {{ $pc['open_issues_count'] }} Tiket Kerusakan Aktif">
                                            🎫
                                        </span>
                                    @endif

                                    @if($pc['status'] === 'broken' && !$hasTicket)
                                        <span class="text-[6px] absolute top-0.5 right-0.5 pointer-events-none">✕</span>
                                    @elseif($pc['status'] === 'warning' && !$hasTicket)
                                        <span class="text-[6px] absolute top-0.5 right-0.5 pointer-events-none">⚡</span>
                                    @endif
                                </button>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>{{-- end lg:col-span-8 --}}

        {{-- Right: Update Seat Location & Status Side Panel --}}
        <div class="lg:col-span-4">
            @if($selectedPc)
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 sticky top-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-blue-600 text-white text-xs font-black rounded-lg shadow-xs">
                            PC #{{ $selectedPc['pc_number'] }}
                        </span>
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">
                            Update Lokasi Tempat Duduk
                        </h3>
                    </div>
                    <button wire:click="closeSelection" title="Batal / Tutup" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg font-bold p-1">✕</button>
                </div>

                {{-- Active Session Warning if in_use --}}
                @if($activeSessionInfo)
                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] uppercase tracking-wider font-extrabold text-blue-700 dark:text-blue-400">⚡ Sesi CBT Aktif</span>
                        <span class="px-2 py-0.5 bg-blue-600 text-white text-[10px] font-black rounded">
                            Peserta #{{ $activeSessionInfo['order'] }}
                        </span>
                    </div>
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $activeSessionInfo['session_title'] }}</p>
                    <div class="flex justify-between items-center text-[11px] text-slate-500 mt-1">
                        <span>Mulai: {{ $activeSessionInfo['start_time'] }} WIB</span>
                        <a href="{{ route('sessions.show', $activeSessionInfo['session_id']) }}" class="text-blue-600 hover:underline font-semibold">
                            Lihat Sesi →
                        </a>
                    </div>
                </div>
                @endif

                {{-- Damage Issue Tickets List --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <span>📋 Tiket Kerusakan PC</span>
                            @if(count($pcIssues) > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300">
                                    {{ count($pcIssues) }} Tiket
                                </span>
                            @endif
                        </label>
                    </div>

                    @if(count($pcIssues) > 0)
                        <div class="space-y-2.5 max-h-[220px] overflow-y-auto pr-1">
                            @foreach($pcIssues as $issue)
                            @php
                                $statusBg = match($issue['status']) {
                                    'open'        => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300 border-red-200 dark:border-red-800',
                                    'in_progress' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                    'resolved'    => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 border-green-200 dark:border-green-800',
                                    default       => 'bg-slate-100 text-slate-800',
                                };
                                $statusLabel = match($issue['status']) {
                                    'open'        => 'Terbuka',
                                    'in_progress' => 'Dalam Proses',
                                    'resolved'    => 'Selesai',
                                    default       => 'Unknown',
                                };
                                $severityLabel = match($issue['severity']) {
                                    'critical' => '🔴 Kritis',
                                    'high'     => '🟠 Tinggi',
                                    'medium'   => '🟡 Sedang',
                                    'low'      => '🔵 Rendah',
                                    default    => 'Sedang',
                                };
                                $categoryLabel = match($issue['category']) {
                                    'monitor'     => '🖥️ Monitor',
                                    'cpu'         => '💻 Unit PC / CPU',
                                    'network'     => '🌐 Jaringan / LAN',
                                    'peripherals' => '⌨️ Periferal',
                                    'software'    => '⚙️ Perangkat Lunak',
                                    default       => 'Kendala PC',
                                };
                            @endphp
                            <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200 dark:border-slate-700 text-xs">
                                <div class="flex items-center justify-between mb-1.5 gap-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1">
                                        {{ $categoryLabel }}
                                    </span>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $statusBg }}">
                                            {{ $statusLabel }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200">
                                            {{ $severityLabel }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed mb-2 font-medium">
                                    {{ $issue['issue_description'] }}
                                </p>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1.5 border-t border-slate-200/60 dark:border-slate-600/60">
                                    <span>Oleh: <strong class="text-slate-600 dark:text-slate-300">{{ $issue['reporter']['name'] ?? 'Sistem/Admin' }}</strong></span>
                                    <a href="{{ route('issues.show', $issue['id']) }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">
                                        Detail Tiket #{{ $issue['id'] }} →
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-center">
                            <p class="text-xs text-slate-400 italic">Tidak ada tiket kerusakan aktif untuk PC ini.</p>
                        </div>
                    @endif
                </div>

                {{-- Seat Location & Status Edit Form --}}
                <div class="space-y-4 mb-4">
                    {{-- Select Row & Column Grid Position --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            📍 Posisi Lokasi Denah (Baris × Kolom)
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[11px] text-slate-500 mb-1">Baris (Row)</label>
                                <select wire:model="newRow" class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-xs bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500">
                                    @for($r = 1; $r <= 8; $r++)
                                        <option value="{{ $r }}">Baris R{{ $r }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-slate-500 mb-1">Kolom (Column)</label>
                                <select wire:model="newCol" class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-xs bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold focus:ring-2 focus:ring-blue-500">
                                    @for($c = 1; $c <= 11; $c++)
                                        <option value="{{ $c }}">Kolom {{ $c }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5 italic">
                            *Jika lokasi tujuan sudah diisi PC lain, posisi kedua PC akan otomatis bertukar (swap).
                        </p>
                    </div>

                    {{-- Select Status --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                            🛠️ Status Tempat Duduk & PC
                        </label>
                        <div class="grid grid-cols-1 gap-1.5">
                            <button type="button" wire:click="$set('newStatus', 'available')"
                                class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-between border-2 {{ $newStatus === 'available' ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 hover:border-blue-400' }}">
                                <span>🔵 Siap Digunakan</span>
                                <span class="text-[10px] text-blue-600 font-normal">Kondisi Prima</span>
                            </button>
                            <button type="button" wire:click="$set('newStatus', 'warning')"
                                class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-between border-2 {{ $newStatus === 'warning' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 hover:border-orange-400' }}">
                                <span>🟠 Rusak Ringan (Bisa Digunakan)</span>
                                <span class="text-[10px] text-orange-600 font-normal">Dapat Dipakai</span>
                            </button>
                            <button type="button" wire:click="$set('newStatus', 'broken')"
                                class="py-2 px-3 rounded-xl text-xs font-bold transition flex items-center justify-between border-2 {{ $newStatus === 'broken' ? 'border-red-500 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 hover:border-red-400' }}">
                                <span>🔴 Rusak (Tidak Bisa Digunakan)</span>
                                <span class="text-[10px] text-red-600 font-normal">Nonaktif</span>
                            </button>
                        </div>
                    </div>

                    {{-- PC Info Summary --}}
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-3 text-xs space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">IP Address</span>
                            <span class="font-mono text-slate-700 dark:text-slate-300">{{ $selectedPc['ip_address'] ?? '192.168.1.' . $selectedPc['pc_number'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Penggunaan</span>
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ round($selectedPc['total_usage_minutes'] / 60, 1) }} jam ({{ $selectedPc['total_sessions_count'] }} sesi)</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 pt-2 border-t border-slate-100 dark:border-slate-700">
                    <button wire:click="updateLocationAndStatus" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">
                        <span>💾 Simpan Lokasi & Status</span>
                    </button>
                    <button wire:click="openIssueModal({{ $selectedPc['id'] }})" class="py-2.5 px-3 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl transition">
                        + Buat Tiket
                    </button>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 text-center sticky top-6 flex flex-col items-center justify-center min-h-[340px]">
                <div class="w-14 h-14 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mb-3 shadow-xs">
                    📍
                </div>
                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">Update Lokasi Tempat Duduk PC</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-xs leading-relaxed">
                    Klik salah satu nomor tempat duduk PC pada denah di sebelah kiri untuk mengubah posisi baris, kolom, atau status PC.
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Issue Modal --}}
    @if($showIssueModal && $selectedPc)
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" wire:click.self="closeModals">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md p-6" wire:click.stop>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">
                    🔴 Laporkan Kerusakan — PC #{{ $selectedPc['pc_number'] }}
                </h3>
                <button wire:click="closeModals" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori Kendala</label>
                    <select wire:model="issueForm.category" class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih kategori...</option>
                        <option value="monitor">Monitor (Mati, flicker, garis)</option>
                        <option value="cpu">Unit PC / CPU (Boot gagal, BSOD)</option>
                        <option value="network">Jaringan / LAN (Kabel, IP konflik)</option>
                        <option value="peripherals">Periferal (Keyboard, Mouse, Audio)</option>
                        <option value="software">Perangkat Lunak / OS / CBT Browser</option>
                    </select>
                    @error('issueForm.category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tingkat Keparahan</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['low' => ['label' => 'Rendah', 'color' => 'blue'], 'medium' => ['label' => 'Sedang', 'color' => 'yellow'], 'high' => ['label' => 'Tinggi', 'color' => 'orange'], 'critical' => ['label' => 'Kritis', 'color' => 'red']] as $val => $cfg)
                        <button type="button" wire:click="$set('issueForm.severity', '{{ $val }}')"
                            class="py-2 rounded-lg text-xs font-medium border-2 transition
                                {{ $issueForm['severity'] === $val ? 'border-' . $cfg['color'] . '-500 bg-' . $cfg['color'] . '-100 dark:bg-' . $cfg['color'] . '-900/30 text-' . $cfg['color'] . '-800 dark:text-' . $cfg['color'] . '-300' : 'border-slate-200 dark:border-slate-600 text-slate-500' }}">
                            {{ $cfg['label'] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi Masalah</label>
                    <textarea wire:model="issueForm.issue_description" rows="3"
                        placeholder="Jelaskan detail masalah yang terjadi..."
                        class="w-full border border-slate-300 dark:border-slate-600 rounded-xl px-3 py-2 text-sm bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                    @error('issueForm.issue_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-5">
                <button wire:click="closeModals" class="flex-1 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    Batal
                </button>
                <button wire:click="submitIssue" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition">
                    Laporkan
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
