@extends('layout.layout')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Form Cari & Proses Pembayaran (Panel Kiri) -->
    <div class="bg-white border border-slate-300 rounded shadow-sm h-fit">
        <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
            Cari & Proses Transaksi Keluar
        </div>

        <div class="p-4">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-3 py-2 rounded text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('petugas.transaksi.keluar') }}" method="GET" class="flex gap-2 mb-4">
                <input type="text" name="no_plat" value="{{ request('no_plat') }}" placeholder="Cari No Plat (contoh: B 1234 ABC)" required
                    class="flex-1 border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600 uppercase">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded text-sm font-semibold transition">
                    Cari
                </button>
            </form>

            @if(request('no_plat') && !$transaksi)
                <div class="bg-slate-100 border border-slate-400 text-slate-700 p-3 rounded text-xs">
                    Kendaraan dengan Plat <strong>{{ strtoupper(request('no_plat')) }}</strong> tidak ditemukan atau sudah keluar.
                </div>
            @endif

            @if($transaksi)
                @php
                    $member = \App\Models\Kendaraan::where('no_plat', $transaksi->no_plat)->first();
                    $tagihanAkhir = $member ? 0 : $transaksi->biaya_temp;
                @endphp

                <div class="border border-slate-300 rounded p-4 bg-slate-50 space-y-3">
                    <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                        <span class="text-sm font-bold text-slate-800">Detail Pembayaran Parkir</span>
                        @if($member)
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300">
                                ✓ Member ({{ $member->pemilik }})
                            </span>
                        @else
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-200 text-slate-700">
                                Pengunjung Umum
                            </span>
                        @endif
                    </div>

                    <div class="flex justify-between text-xs text-slate-600">
                        <span>No Plat:</span>
                        <span class="font-bold text-slate-800">{{ $transaksi->no_plat }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Jenis / Tarif:</span>
                        <span>{{ $transaksi->tarif->jenis_kendaraan }} (Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}/jam)</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Waktu Masuk:</span>
                        <span>{{ $transaksi->waktu_masuk }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Waktu Keluar:</span>
                        <span>{{ $transaksi->waktu_keluar_temp }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Durasi:</span>
                        <span><strong>{{ $transaksi->durasi_temp }} Jam</strong></span>
                    </div>

                    <!-- Kotak Total Tagihan -->
                    <div class="border-t border-slate-300 pt-3 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-sm text-slate-800 block">TOTAL BIAYA:</span>
                            @if($member)
                                <span class="text-[11px] text-emerald-700 font-medium">Gratis khusus kendaraan langganan</span>
                            @endif
                        </div>
                        <span class="font-bold text-lg text-slate-900">Rp {{ number_format($tagihanAkhir, 0, ',', '.') }}</span>
                    </div>

                    <!-- Form Input Pembayaran Kasir -->
                    <form action="{{ route('petugas.transaksi.keluar.store', $transaksi->id_parkir) }}" method="POST" class="pt-3 border-t border-slate-200 space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="bg-white border border-slate-300 rounded px-3 py-2 flex items-center gap-2 cursor-pointer hover:bg-slate-100">
                                    <input type="radio" name="metode_pembayaran" value="Tunai" checked class="metode-radio">
                                    <span class="text-xs font-semibold text-slate-700">Tunai (Cash)</span>
                                </label>
                                <label class="bg-white border border-slate-300 rounded px-3 py-2 flex items-center gap-2 cursor-pointer hover:bg-slate-100">
                                    <input type="radio" name="metode_pembayaran" value="QRIS" class="metode-radio">
                                    <span class="text-xs font-semibold text-slate-700">QRIS / Non-Tunai</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Uang Diterima (Rp)</label>
                            <input type="number" id="uang_bayar" name="uang_bayar" min="0" step="500"
                                value="{{ $tagihanAkhir }}" required
                                class="w-full bg-white border border-slate-300 rounded px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:border-slate-600">
                        </div>

                        @if($tagihanAkhir > 0)
                        <!-- Tombol Pecahan Uang Cepat -->
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" data-nominal="{{ $tagihanAkhir }}" class="btn-cepat px-2 py-1 text-[11px] bg-emerald-100 text-emerald-800 font-semibold rounded border border-emerald-300 hover:bg-emerald-200">
                                Uang Pas
                            </button>
                            @foreach([5000, 10000, 20000, 50000, 100000] as $pecahan)
                                @if($pecahan >= $tagihanAkhir)
                                <button type="button" data-nominal="{{ $pecahan }}" class="btn-cepat px-2 py-1 text-[11px] bg-white text-slate-700 font-medium rounded border border-slate-300 hover:bg-slate-100">
                                    Rp {{ number_format($pecahan, 0, ',', '.') }}
                                </button>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <!-- Indikator Kembalian Otomatis -->
                        <div id="box-kembalian" class="p-2.5 rounded border flex justify-between items-center bg-emerald-50 border-emerald-200 text-emerald-800">
                            <span class="text-xs font-semibold uppercase">Kembalian:</span>
                            <span id="teks-kembalian" class="text-sm font-bold">Rp 0</span>
                        </div>

                        <button type="submit" id="btn-submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded text-sm font-semibold transition">
                            Proses Bayar & Cetak Struk
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Kendaraan Parkir Saat Ini (Panel Kanan) -->
    <div class="bg-white border border-slate-300 rounded shadow-sm">
        <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
            Daftar Kendaraan Sedang Parkir ({{ $daftarParkir->count() }})
        </div>

        <div class="p-4 overflow-x-auto">
            <table class="w-full border-collapse border border-slate-300 text-xs text-left">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                        <th class="p-2 border border-slate-300">Plat</th>
                        <th class="p-2 border border-slate-300">Jenis</th>
                        <th class="p-2 border border-slate-300">Area</th>
                        <th class="p-2 border border-slate-300">Jam Masuk</th>
                        <th class="p-2 border border-slate-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarParkir as $p)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="p-2 border border-slate-300 font-bold">{{ $p->no_plat }}</td>
                            <td class="p-2 border border-slate-300">{{ $p->tarif->jenis_kendaraan }}</td>
                            <td class="p-2 border border-slate-300">{{ $p->area->nama_area }}</td>
                            <td class="p-2 border border-slate-300">{{ date('H:i d/m', strtotime($p->waktu_masuk)) }}</td>
                            <td class="p-2 border border-slate-300 text-center">
                                <a href="{{ route('petugas.transaksi.keluar', ['no_plat' => $p->no_plat]) }}" 
                                   class="bg-slate-700 hover:bg-slate-800 text-white px-2.5 py-1 rounded text-[10px] font-semibold">
                                    Pilih
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-500">Tidak ada kendaraan sedang parkir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@if($transaksi)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalTagihan = {{ (int) $tagihanAkhir }};
        const inputBayar   = document.getElementById('uang_bayar');
        const boxKembalian = document.getElementById('box-kembalian');
        const teksKembali  = document.getElementById('teks-kembalian');
        const btnSubmit    = document.getElementById('btn-submit');
        const radios       = document.querySelectorAll('.metode-radio');
        const btnCepat     = document.querySelectorAll('.btn-cepat');

        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        function hitungKembalian() {
            const bayar   = parseFloat(inputBayar.value) || 0;
            const selisih = bayar - totalTagihan;

            if (selisih < 0) {
                boxKembalian.className = 'p-2.5 rounded border flex justify-between items-center bg-red-50 border-red-200 text-red-700';
                teksKembali.textContent = 'Kurang ' + formatRupiah(Math.abs(selisih));
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                boxKembalian.className = 'p-2.5 rounded border flex justify-between items-center bg-emerald-50 border-emerald-200 text-emerald-800';
                teksKembali.textContent = formatRupiah(selisih);
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        inputBayar.addEventListener('input', hitungKembalian);

        btnCepat.forEach(btn => {
            btn.addEventListener('click', function () {
                inputBayar.value = this.dataset.nominal;
                hitungKembalian();
            });
        });

        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'QRIS') {
                    inputBayar.value = totalTagihan;
                    inputBayar.readOnly = true;
                    inputBayar.classList.add('bg-slate-200');
                } else {
                    inputBayar.readOnly = false;
                    inputBayar.classList.remove('bg-slate-200');
                }
                hitungKembalian();
            });
        });

        hitungKembalian();
    });
</script>
@endif
@endsection