<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Kontrol PC - Lab CBT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .paper-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            padding: 18mm 20mm;
            margin: 20px auto;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        /* Grid Table Styles */
        .control-grid-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000000;
            margin: 12px 0 24px 0;
            table-layout: fixed;
        }

        .control-grid-table td {
            border: 1.5px solid #000000;
            text-align: center;
            vertical-align: middle;
            height: 48px;
            width: 9.09%;
            padding: 0;
            position: relative;
            background: #ffffff;
        }

        .pc-cell-content {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .pc-num {
            font-size: 15px;
            font-weight: 700;
            color: #000000;
            line-height: 1;
            z-index: 2;
        }

        /* Circled Number (Warning / Ringan) */
        .circled-pc {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: 2px solid #000000;
            border-radius: 50%;
            font-weight: 800;
            z-index: 2;
        }

        /* Cross Marker (Broken / Rusak) */
        .cross-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 3;
        }

        .cross-overlay svg {
            width: 100%;
            height: 100%;
        }

        .tag-issue-cat {
            position: absolute;
            top: 2px;
            left: 3px;
            font-size: 7px;
            font-weight: 800;
            color: #000000;
            text-transform: uppercase;
            line-height: 1;
            letter-spacing: -0.2px;
            z-index: 4;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .paper-sheet {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                min-height: auto !important;
            }
        }
    </style>
</head>
<body class="p-0 sm:p-6">

    {{-- Screen Toolbar --}}
    <div class="no-print max-w-4xl mx-auto mb-6 bg-slate-900 text-white rounded-2xl p-4 shadow-xl flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('floor-plan') }}" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition flex items-center gap-1.5">
                <span>←</span> Kembali ke Denah
            </a>
            <div>
                <h1 class="text-sm font-bold text-white">Lembar Kontrol PC Lab CBT</h1>
                <p class="text-[11px] text-slate-400">Pratinjau cetak formulir kontrol fisik 88 unit PC</p>
            </div>
        </div>

        {{-- Filter Hari/Tanggal/Jam --}}
        <form method="GET" action="{{ route('floor-plan.control-sheet') }}" class="flex flex-wrap items-center gap-2.5">
            <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-700">
                <label class="text-[11px] text-slate-300 font-semibold">Tgl:</label>
                <input type="date" name="date" value="{{ $customDate->format('Y-m-d') }}"
                    class="bg-white text-black font-bold border border-slate-300 rounded-lg px-2.5 py-1 text-xs outline-none focus:ring-2 focus:ring-blue-500 shadow-inner">
            </div>
            <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1.5 rounded-xl border border-slate-700">
                <label class="text-[11px] text-slate-300 font-semibold">Jam:</label>
                <input type="text" name="time" value="{{ $customTime }}" placeholder="08:00"
                    class="bg-white text-black font-bold border border-slate-300 rounded-lg px-2.5 py-1 text-xs w-20 text-center outline-none focus:ring-2 focus:ring-blue-500 shadow-inner">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-xs font-bold rounded-xl text-white shadow-sm transition cursor-pointer">
                Terapkan
            </button>
        </form>

        <div class="flex items-center gap-2">
            <a href="{{ route('floor-plan.control-sheet.pdf', ['date' => $customDate->format('Y-m-d'), 'time' => $customTime]) }}"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                <span>📥 Unduh PDF</span>
            </a>
            <button onclick="window.print()"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold rounded-xl transition flex items-center gap-1.5 shadow-md cursor-pointer">
                <span>🖨️ Cetak Sekarang</span>
            </button>
        </div>
    </div>

    {{-- The Printable Paper Sheet --}}
    <div class="paper-sheet">
        
        {{-- Document Header --}}
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black tracking-widest text-black uppercase" style="letter-spacing: 0.12em;">
                LEMBAR KONTROL PC
            </h2>
            <p class="text-sm font-bold tracking-wider text-black uppercase mt-1" style="letter-spacing: 0.2em;">
                LAB CBT FK UNTAN
            </p>
        </div>

        {{-- Meta Header: Hari, Tanggal, Jam --}}
        <div class="text-sm font-semibold text-black space-y-1 mb-4 leading-snug">
            <p><span class="font-normal">Hari:</span> <strong class="font-bold">{{ $hari }}</strong></p>
            <p><span class="font-normal">Tanggal:</span> <strong class="font-bold">{{ $tanggal }}</strong></p>
            <p><span class="font-normal">Jam:</span> <strong class="font-bold">{{ $jam }}</strong></p>
        </div>

        {{-- 8x11 Grid Matrix --}}
        <table class="control-grid-table">
            <tbody>
                @for($r = 1; $r <= 8; $r++)
                <tr>
                    @for($c = 1; $c <= 11; $c++)
                        @php
                            $pc = $grid[$r][$c] ?? null;
                            $pcNum = $pc ? $pc->pc_number : '';
                            $status = $pc ? $pc->status : 'available';
                            $latestIssue = ($pc && $pc->openIssues->isNotEmpty()) ? $pc->openIssues->first() : null;
                            $catLabel = $latestIssue ? match($latestIssue->category) {
                                'monitor'     => 'Monitor',
                                'cpu'         => 'CPU',
                                'network'     => 'LAN',
                                'peripherals' => 'Key/Mouse',
                                'software'    => 'Software',
                                default       => '',
                            } : '';
                        @endphp
                        <td>
                            <div class="pc-cell-content">
                                @if($catLabel)
                                    <span class="tag-issue-cat">{{ $catLabel }}</span>
                                @endif

                                @if($status === 'warning')
                                    {{-- Rusak Ringan: Lingkaran di sekeliling angka --}}
                                    <span class="circled-pc">{{ $pcNum }}</span>
                                @else
                                    <span class="pc-num">{{ $pcNum }}</span>
                                @endif

                                @if($status === 'broken')
                                    {{-- Rusak Berat: Garis Silang (Cross lines X) diagonal --}}
                                    <div class="cross-overlay">
                                        <svg viewBox="0 0 100 100" preserveAspectRatio="none">
                                            <line x1="0" y1="0" x2="100" y2="100" stroke="#000000" stroke-width="3" stroke-linecap="round"/>
                                            <line x1="0" y1="100" x2="100" y2="0" stroke="#000000" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>

        {{-- Section INVENTARIS & Catatan --}}
        <div class="mt-8">
            <h3 class="text-base font-extrabold uppercase text-black tracking-wider mb-2">
                INVENTARIS
            </h3>
            <div class="w-full h-0.5 bg-black mb-4"></div>

            <div class="grid grid-cols-12 gap-6 text-sm text-black">
                <div class="col-span-7 space-y-2">
                    <ul class="space-y-1.5 list-disc list-inside">
                        @foreach($brands as $brand => $count)
                            <li><strong class="font-bold">{{ $count }} {{ $brand ?? 'PC Client' }}</strong></li>
                        @endforeach
                        <li><strong class="font-bold">{{ $availablePcs }} PC Siap Digunakan</strong> (Normal)</li>
                        @if($warningPcs > 0 || $brokenPcs > 0)
                            <li>
                                <strong class="font-bold">{{ $warningPcs + $brokenPcs }} PC Perlu Perhatian</strong>
                                <span class="text-xs">({{ $warningPcs }} rusak ringan, {{ $brokenPcs }} rusak berat)</span>
                            </li>
                        @endif
                        <li>
                            <strong class="font-bold">Monitor</strong>
                            <ul class="pl-6 pt-1 space-y-1 list-circle">
                                @foreach($monitors as $monitor => $mCount)
                                    <li>{{ $mCount }} {{ $monitor ?? 'Monitor Standar' }}</li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    @if($activeIssues->isNotEmpty())
                    <div class="pt-2">
                        <p class="font-bold text-xs uppercase tracking-wider mb-1">Catatan Kerusakan Aktif:</p>
                        <ul class="text-xs space-y-0.5 list-inside list-square pl-1">
                            @foreach($activeIssues->take(6) as $issue)
                                <li>
                                    <strong>PC #{{ $issue->computer->pc_number }}</strong>: {{ $issue->getCategoryLabel() }} — {{ Str::limit($issue->issue_description, 45) }}
                                </li>
                            @endforeach
                            @if($activeIssues->count() > 6)
                                <li class="text-slate-600 italic">...dan {{ $activeIssues->count() - 6 }} kendala lainnya terdaftar di sistem.</li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </div>

                {{-- Signatures Column --}}
                <div class="col-span-5 flex flex-col justify-between items-center text-center pl-6 border-l border-slate-300">
                    <div>
                        <p class="text-xs text-slate-600">Pontianak, {{ $tanggal }}</p>
                        <p class="text-xs font-bold text-black mt-1">Petugas / Teknisi Lab CBT</p>
                    </div>

                    <div class="w-full pt-16">
                        <div class="w-44 mx-auto border-b border-black"></div>
                        <p class="text-xs font-bold text-black mt-1.5">{{ Auth::user()->name ?? 'Administrator Lab' }}</p>
                        <p class="text-[10px] text-slate-500">NIP / ID Petugas</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
