<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Parkir - {{ $transaksi->no_plat }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 280px; margin: 0 auto; padding: 10px; font-size: 12px; }
        .text-center { text-align: center; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .d-flex { display: flex; justify-content: space-between; }
        .btn-kembali { display: inline-block; padding: 6px 12px; background: #333; color: #fff; text-decoration: none; border-radius: 4px; font-size: 11px; font-family: sans-serif; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <strong>STRUK PARKIR REGULER</strong><br>
        <small>Area: {{ $transaksi->area->nama_area }}</small>
    </div>

    <div class="line"></div>

    <div class="d-flex"><span>No Trx:</span> <span>#{{ $transaksi->id_parkir }}</span></div>
    <div class="d-flex"><span>No Plat:</span> <span><strong>{{ $transaksi->no_plat }}</strong></span></div>
    <div class="d-flex"><span>Jenis:</span> <span>{{ $transaksi->tarif->jenis_kendaraan }}</span></div>
    <div class="d-flex"><span>Masuk:</span> <span>{{ date('d/m/Y H:i', strtotime($transaksi->waktu_masuk)) }}</span></div>

    @if($transaksi->status == 'Keluar')
        <div class="d-flex"><span>Keluar:</span> <span>{{ date('d/m/Y H:i', strtotime($transaksi->waktu_keluar)) }}</span></div>
        <div class="d-flex"><span>Durasi:</span> <span>{{ $transaksi->durasi_jam }} Jam</span></div>
        <div class="line"></div>
        <div class="d-flex" style="font-size: 13px;">
            <span><strong>TOTAL:</strong></span>
            <span><strong>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</strong></span>
        </div>
    @else
        <div class="line"></div>
        <div class="text-center">
            <small>Simpan struk ini sebagai bukti masuk parkir.</small>
        </div>
    @endif

    <div class="line"></div>
    <div class="text-center">
        <small>Petugas: {{ $transaksi->user->nama_lengkap }}</small><br>
        <small>Terima Kasih</small>
    </div>

    <div class="text-center no-print" style="margin-top: 15px;">
        <a href="{{ route('petugas.transaksi.masuk') }}" class="btn-kembali">Kembali ke Transaksi</a>
    </div>

</body>
</html>