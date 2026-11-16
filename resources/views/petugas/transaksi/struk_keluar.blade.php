@extends('layout.layout')

@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #area-struk, #area-struk * {
            visibility: visible;
        }
        #area-struk {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 320px;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            padding: 8px !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="max-w-xs mx-auto space-y-3">
    @if(session('success'))
        <div class="no-print bg-emerald-50 border border-emerald-300 text-emerald-800 px-3 py-2 rounded text-xs text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    @php
        $member = \App\Models\Kendaraan::where('no_plat', $transaksi->no_plat)->first();
    @endphp

    <!-- Area Struk Thermal -->
    <div id="area-struk" class="bg-white border border-slate-300 rounded p-5 shadow-sm font-mono text-xs text-slate-800">
        <!-- Header Struk -->
        <div class="text-center border-b border-dashed border-slate-400 pb-3 mb-3">
            <div class="font-bold text-sm uppercase tracking-wide">E-Parkir System</div>
            <div class="text-[11px] text-slate-600">Struk Pembayaran Parkir Keluar</div>
            <div class="text-[10px] text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d/m/Y H:i:s') }}</div>
        </div>

        <!-- Detail Kendaraan & Waktu -->
        <div class="space-y-1.5 border-b border-dashed border-slate-400 pb-3 mb-3">
            <div class="flex justify-between">
                <span class="text-slate-600">No. Transaksi</span>
                <span class="font-bold">#{{ str_pad($transaksi->id_parkir, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">No. Plat</span>
                <span class="font-bold text-sm">{{ $transaksi->no_plat }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Status</span>
                <span class="font-semibold">{{ $member ? 'MEMBER LANGGANAN' : 'UMUM' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Jenis / Area</span>
                <span>{{ $transaksi->tarif->jenis_kendaraan }} / {{ $transaksi->area->nama_area }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Waktu Masuk</span>
                <span>{{ \Carbon\Carbon::parse($transaksi->waktu_masuk)->format('d/m/y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Waktu Keluar</span>
                <span>{{ \Carbon\Carbon::parse($transaksi->waktu_keluar)->format('d/m/y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Durasi Parkir</span>
                <span class="font-bold">{{ $transaksi->durasi_jam }} Jam</span>
            </div>
        </div>

        <!-- Rincian Pembayaran -->
        <div class="space-y-1.5 border-b border-dashed border-slate-400 pb-3 mb-3">
            <div class="flex justify-between">
                <span class="text-slate-600">Tarif / Jam</span>
                <span>{{ $member ? 'Rp 0 (Member)' : 'Rp ' . number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Metode Bayar</span>
                <span class="font-bold">{{ $transaksi->metode_pembayaran ?? 'Tunai' }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold pt-1 border-t border-dotted border-slate-300">
                <span>TOTAL BAYAR</span>
                <span>Rp {{ number_format($transaksi->biaya_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-600">Uang Diterima</span>
                <span>Rp {{ number_format($transaksi->uang_bayar ?? $transaksi->biaya_total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaksi->kembalian ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer Struk -->
        <div class="text-center text-[11px] text-slate-600 space-y-1">
            <div>Petugas: <strong>{{ $transaksi->user->nama_lengkap ?? $transaksi->user->name ?? $transaksi->user->username ?? 'Petugas' }}</strong></div>
            <div class="text-[10px] text-slate-500 pt-1">Terima kasih atas kunjungan Anda.<br>Simpan struk ini sebagai bukti pembayaran sah.</div>
        </div>
    </div>

    <!-- Tombol Aksi (Tidak ikut tercetak) -->
    <div class="no-print flex gap-2">
        <button type="button" onclick="window.print()" class="w-1/2 bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded text-xs font-semibold transition">
            Cetak Struk
        </button>
        <a href="{{ route('petugas.transaksi.keluar') }}" class="w-1/2 text-center bg-slate-200 hover:bg-slate-300 text-slate-800 py-2.5 rounded text-xs font-semibold transition">
            Selesai
        </a>
    </div>
</div>
@endsection
