@extends('layout.layout')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Form Cari & Proses Pembayaran -->
    <div class="bg-white border border-slate-300 rounded shadow-sm h-fit">
        <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
            Cari & Process Transaksi Keluar
        </div>

        <div class="p-4">
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
                <div class="border border-slate-300 rounded p-4 bg-slate-50 space-y-3">
                    <div class="text-sm font-bold border-b border-slate-200 pb-2 text-slate-800">Detail Pembayaran Parkir</div>
                    
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>No Plat:</span>
                        <span class="font-bold text-slate-800">{{ $transaksi->no_plat }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Jenis:</span>
                        <span>{{ $transaksi->tarif->jenis_kendaraan }}</span>
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
                    
                    <div class="border-t border-slate-300 pt-3 flex justify-between items-center">
                        <span class="font-bold text-sm text-slate-800">TOTAL BIAYA:</span>
                        <span class="font-bold text-lg text-slate-900">Rp {{ number_format($transaksi->biaya_temp, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ route('petugas.transaksi.keluar.store', $transaksi->id_parkir) }}" method="POST" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2 rounded text-sm font-semibold transition">
                            Proses Bayar & Selesai
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Kendaraan Parkir Saat Ini -->
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
                                   class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1 rounded text-[10px]">
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
@endsection