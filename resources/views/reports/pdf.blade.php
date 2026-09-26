<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Beban PC Lab CBT</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .subtitle { color: #64748b; font-size: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #1e293b; color: white; padding: 6px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.05em; }
        td { padding: 5px 10px; border-bottom: 1px solid #e2e8f0; }
        tr:nth-child(even) { background: #f8fafc; }
        .stats { display: flex; gap: 20px; margin-bottom: 15px; }
        .stat-box { flex: 1; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; text-align: center; }
        .stat-num { font-size: 20px; font-weight: bold; color: #3b82f6; }
        .stat-lbl { font-size: 9px; color: #94a3b8; text-transform: uppercase; }
        .badge-available { color: #64748b; }
        .badge-broken { color: #dc2626; font-weight: bold; }
        .badge-warning { color: #ca8a04; }
        .badge-in-use { color: #16a34a; }
    </style>
</head>
<body>
    <h1>Laporan Beban PC — Lab CBT</h1>
    <p class="subtitle">Digenerate pada: {{ $generatedAt }} WIB</p>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-num">{{ $summary['total_pcs'] }}</div>
            <div class="stat-lbl">Total PC</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ number_format($summary['total_hours'], 0) }}</div>
            <div class="stat-lbl">Total Jam Lab</div>
        </div>
        <div class="stat-box">
            <div class="stat-num">{{ $summary['avg_hours_per_pc'] }}</div>
            <div class="stat-lbl">Rata-rata Jam/PC</div>
        </div>
        <div class="stat-box">
            <div class="stat-num" style="color:#dc2626">{{ $summary['broken'] }}</div>
            <div class="stat-lbl">PC Rusak</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. PC</th>
                <th>Posisi</th>
                <th>Brand/Model</th>
                <th>IP Address</th>
                <th>Status</th>
                <th>Total Jam</th>
                <th>Total Sesi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($computers as $pc)
            <tr>
                <td><strong>PC #{{ $pc->pc_number }}</strong></td>
                <td>B{{ $pc->row_position }}-K{{ $pc->col_position }}</td>
                <td>{{ $pc->brand_model ?? '-' }}</td>
                <td>{{ $pc->ip_address ?? '-' }}</td>
                <td class="badge-{{ $pc->status }}">{{ $pc->getStatusLabel() }}</td>
                <td>{{ round($pc->total_usage_minutes / 60, 1) }} jam</td>
                <td>{{ $pc->total_sessions_count }} sesi</td>
                <td>{{ Str::limit($pc->notes ?? '-', 30) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
