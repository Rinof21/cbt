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
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
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
            table-layout: fixed;
            margin: 0;
        }

        table.grid-matrix td {
            border: 1.5px solid #000000;
            height: 32px;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
            font-weight: normal;
            padding: 0;
            position: relative;
        }

        .damage-box {
            border: 1px solid #000000;
            border-radius: 4px;
            padding: 6px 8px;
            background-color: #ffffff;
        }

        .damage-header {
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #000000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .cell-available {
            background-color: #eff6ff;
            color: #1e3a8a;
        }

        .cell-broken {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .cell-warning {
            background-color: #fef3c7;
            color: #b45309;
        }

        .warning-num {
            color: #b45309;
            font-weight: normal;
            font-size: 11px;
        }

        .strikethrough-num {
            text-decoration: line-through;
            color: #dc2626;
            font-weight: normal;
            font-size: 11px;
        }

        .sub-tag {
            display: block;
            font-size: 6px;
            line-height: 1;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 1px;
            color: #000000;
        }

        /* Section Inventaris */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .divider {
            border-bottom: 1.5px solid #000000;
            margin-bottom: 10px;
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
            padding-left: 15px;
            line-height: 1.5;
            font-size: 10px;
        }

        ul.inv-list li {
            margin-bottom: 2px;
        }

        ul.sub-list {
            padding-left: 15px;
            list-style-type: circle;
        }

        .signature-box {
            text-align: center;
            font-size: 10px;
            width: 200px;
            margin-left: auto;
        }

        .signature-space {
            height: 45px;
        }

        .signature-line {
            border-bottom: 1px solid #000000;
            width: 160px;
            margin: 0 auto 3px auto;
        }
    </style>
</head>
<body>

    <div class="title-header">
        <div>LEMBAR KONTROL PC</div>
        <div class="sub-title-header">LAB CBT FK UNTAN</div>
    </div>

    {{-- Meta Header: Hari, Tanggal, Jam (Vertically Aligned) --}}
    <table style="border-collapse: collapse; margin-bottom: 8px; font-size: 11px;">
        <tr>
            <td style="width: 55px; padding: 1px 0;">Hari</td>
            <td style="width: 10px; padding: 1px 0;">:</td>
            <td style="padding: 1px 0;"><strong>{{ $hari }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Tanggal</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;"><strong>{{ $tanggal }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 1px 0;">Jam</td>
            <td style="padding: 1px 0;">:</td>
            <td style="padding: 1px 0;"><strong>{{ $jam }}</strong></td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Side-by-Side: Table on Left, Catatan Kerusakan on Right --}}
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 14px;">
        <tr>
            <td style="width: 58%; vertical-align: top; padding-right: 8px;">
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
                                <td class="{{ $status === 'broken' ? 'cell-broken' : ($status === 'warning' ? 'cell-warning' : 'cell-available') }}">
                                    @if($catLabel)
                                        <span class="sub-tag">{{ $catLabel }}</span>
                                    @endif

                                    @if($status === 'warning')
                                        <span class="warning-num">{{ $pcNum }}</span>
                                    @elseif($status === 'broken')
                                        <span class="strikethrough-num">{{ $pcNum }}</span>
                                    @else
                                        {{ $pcNum }}
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </td>
            <td style="width: 42%; vertical-align: top; padding-left: 4px;">
                <div class="damage-box">
                    <div class="damage-header">
                        CATATAN KERUSAKAN ({{ $warningPcs + $brokenPcs }} Unit)
                    </div>
                    @if($activeIssues->isNotEmpty())
                        <div style="font-size: 9px; line-height: 1.25;">
                            @foreach($activeIssues->take(6) as $issue)
                            <div style="margin-bottom: 4px; padding-bottom: 3px; border-bottom: 1px solid #e2e8f0;">
                                <div><strong style="color: #b91c1c;">PC #{{ $issue->computer->pc_number }} (R{{ $issue->computer->row_position }}-K{{ $issue->computer->col_position }})</strong> — [{{ $issue->getCategoryLabel() }}]</div>
                                <div style="color: #334155; font-size: 8px;">{{ Str::limit($issue->issue_description, 38) }}</div>
                            </div>
                            @endforeach
                            @if($activeIssues->count() > 6)
                                <div style="font-style: italic; font-size: 8px; color: #64748b;">+ {{ $activeIssues->count() - 6 }} kendala lainnya di sistem.</div>
                            @endif
                        </div>
                    @else
                        <div style="font-style: italic; font-size: 9.5px; color: #64748b; padding: 6px 0;">
                            Semua unit PC dalam kondisi prima.
                        </div>
                    @endif

                    <div style="margin-top: 6px; border-top: 1px dashed #94a3b8; padding-top: 3px;">
                        <div style="font-size: 8px; font-weight: bold; color: #475569;">CATATAN PEMERIKSAAN:</div>
                        <div style="border-bottom: 1px solid #cbd5e1; height: 10px;"></div>
                        <div style="border-bottom: 1px solid #cbd5e1; height: 10px;"></div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Section Inventaris --}}
    <div class="section-title">INVENTARIS</div>
    <div class="divider"></div>

    <table class="footer-layout">
        <tr>
            <td style="width: 60%;">
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
                </ul>
            </td>
            <td style="width: 40%;">
                <div class="signature-box">
                    <div>Pontianak, {{ $tanggal }}</div>
                    <div class="signature-space" style="height: 55px;"></div>
                    <div style="font-weight: bold; margin-bottom: 2px;">{{ Auth::user()->name ?? 'Administrator Lab' }}</div>
                    <div class="signature-line" style="border-bottom: 1.5px solid #000000; width: 150px; margin: 0 auto;"></div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
