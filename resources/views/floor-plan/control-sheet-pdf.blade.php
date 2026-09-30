<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Kontrol PC - Lab CBT</title>
    <style>
        @page {
            margin: 12mm 15mm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        .title-header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .sub-title-header {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .meta-info {
            font-size: 12px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .meta-info strong {
            font-weight: bold;
        }

        /* Grid Table */
        table.grid-matrix {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000000;
            margin-bottom: 25px;
            table-layout: fixed;
        }

        table.grid-matrix td {
            border: 1.5px solid #000000;
            width: 9.09%;
            height: 38px;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
            font-weight: bold;
            padding: 0;
            position: relative;
        }

        .cell-broken {
            background-color: #f3f4f6;
            color: #000000;
            text-decoration: line-through;
            font-weight: 900;
        }

        .cell-warning {
            color: #000000;
        }

        .circle-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 22px;
            border: 2px solid #000000;
            border-radius: 50%;
            text-align: center;
        }

        .cross-mark {
            font-size: 14px;
            font-weight: 900;
        }

        .sub-tag {
            display: block;
            font-size: 7px;
            line-height: 1;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        /* Section Inventaris */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .divider {
            border-bottom: 2px solid #000000;
            margin-bottom: 12px;
        }

        table.footer-layout {
            width: 100%;
            border-collapse: collapse;
        }

        table.footer-layout td {
            vertical-align: top;
        }

        ul.inv-list {
            margin: 0;
            padding-left: 18px;
            line-height: 1.6;
            font-size: 11px;
        }

        ul.inv-list li {
            margin-bottom: 3px;
        }

        ul.sub-list {
            padding-left: 18px;
            list-style-type: circle;
        }

        .signature-box {
            text-align: center;
            font-size: 11px;
            width: 200px;
            margin-left: auto;
        }

        .signature-space {
            height: 60px;
        }

        .signature-line {
            border-bottom: 1px solid #000000;
            width: 160px;
            margin: 0 auto 4px auto;
        }
    </style>
</head>
<body>

    <div class="title-header">
        <div>LEMBAR KONTROL PC</div>
        <div class="sub-title-header">LAB CBT FK UNTAN</div>
    </div>

    <div class="meta-info">
        <div>Hari: <strong>{{ $hari }}</strong></div>
        <div>Tanggal: <strong>{{ $tanggal }}</strong></div>
        <div>Jam: <strong>{{ $jam }}</strong></div>
    </div>

    {{-- 8x11 Grid Table Matrix --}}
    <table class="grid-matrix">
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
                            'monitor'     => 'Mon',
                            'cpu'         => 'CPU',
                            'network'     => 'LAN',
                            'peripherals' => 'Perif',
                            'software'    => 'Soft',
                            default       => '',
                        } : '';
                    @endphp
                    <td class="{{ $status === 'broken' ? 'cell-broken' : ($status === 'warning' ? 'cell-warning' : '') }}">
                        @if($catLabel)
                            <span class="sub-tag">{{ $catLabel }}</span>
                        @endif

                        @if($status === 'warning')
                            <span class="circle-badge">{{ $pcNum }}</span>
                        @elseif($status === 'broken')
                            <span class="cross-mark">✕ {{ $pcNum }} ✕</span>
                        @else
                            {{ $pcNum }}
                        @endif
                    </td>
                @endfor
            </tr>
            @endfor
        </tbody>
    </table>

    {{-- Section Inventaris --}}
    <div class="section-title">INVENTARIS</div>
    <div class="divider"></div>

    <table class="footer-layout">
        <tr>
            <td style="width: 65%;">
                <ul class="inv-list">
                    @foreach($brands as $brand => $count)
                        <li><strong>{{ $count }} {{ $brand ?? 'Unit PC' }}</strong></li>
                    @endforeach
                    <li><strong>{{ $availablePcs }} PC Siap Digunakan</strong> (Normal)</li>
                    @if($warningPcs > 0 || $brokenPcs > 0)
                        <li>
                            <strong>{{ $warningPcs + $brokenPcs }} PC Perlu Perhatian</strong>
                            ({{ $warningPcs }} rusak ringan, {{ $brokenPcs }} rusak berat)
                        </li>
                    @endif
                    <li>
                        <strong>Monitor</strong>
                        <ul class="sub-list">
                            @foreach($monitors as $monitor => $mCount)
                                <li>{{ $mCount }} {{ $monitor ?? 'Monitor' }}</li>
                            @endforeach
                        </ul>
                    </li>
                    @if($activeIssues->isNotEmpty())
                    <li>
                        <strong>Catatan Kerusakan:</strong>
                        <ul class="sub-list" style="font-size: 10px;">
                            @foreach($activeIssues->take(4) as $issue)
                                <li>PC #{{ $issue->computer->pc_number }}: {{ $issue->getCategoryLabel() }} ({{ Str::limit($issue->issue_description, 35) }})</li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                </ul>
            </td>
            <td style="width: 35%;">
                <div class="signature-box">
                    <div>Pontianak, {{ $tanggal }}</div>
                    <div style="font-weight: bold; margin-top: 2px;">Petugas / Teknisi Lab CBT</div>
                    <div class="signature-space"></div>
                    <div class="signature-line"></div>
                    <div style="font-weight: bold;">{{ Auth::user()->name ?? 'Administrator Lab' }}</div>
                    <div style="font-size: 9px; color: #64748b;">NIP / ID Petugas</div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
