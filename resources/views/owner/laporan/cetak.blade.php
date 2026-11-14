<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Parkir</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <h2 style="margin: 0;">LAPORAN REKAPITULASI PENDAPATAN PARKIR</h2>
        <p style="margin: 5px 0;">Sistem Informasi Aplikasi Parkir UKK RPL</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Plat</th>
                <th>Jenis</th>
                <th>Area</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Durasi</th>
                <th class="text-right">Biaya Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $i => $l)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $l->no_plat }}</strong></td>
                    <td>{{ $l->tarif->jenis_kendaraan }}</td>
                    <td>{{ $l->area->nama_area }}</td>
                    <td>{{ $l->waktu_masuk }}</td>
                    <td>{{ $l->waktu_keluar }}</td>
                    <td>{{ $l->durasi_jam }} Jam</td>
                    <td class="text-right">Rp {{ number_format($l->biaya_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>TOTAL PENDAPATAN:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>