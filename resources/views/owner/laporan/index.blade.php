@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700 flex justify-between items-center">
        <span>Laporan Rekapitulasi Transaksi Parkir</span>
        <a href="{{ route('owner.laporan.cetak', request()->all()) }}" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-1.5 rounded">
            Cetak Laporan
        </a>
    </div>

    <div class="p-4">
        <!-- Filter Form -->
        <form action="{{ route('owner.laporan.index') }}" method="GET" class="flex items-end gap-3 mb-4 bg-slate-50 p-3 border border-slate-200 rounded">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="border border-slate-300 rounded px-2 py-1 text-xs">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="border border-slate-300 rounded px-2 py-1 text-xs">
            </div>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white text-xs px-3 py-1.5 rounded">Filter</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-slate-300 text-xs text-left">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                        <th class="p-2 border border-slate-300">No</th>
                        <th class="p-2 border border-slate-300">No Plat</th>
                        <th class="p-2 border border-slate-300">Jenis</th>
                        <th class="p-2 border border-slate-300">Area</th>
                        <th class="p-2 border border-slate-300">Waktu Masuk</th>
                        <th class="p-2 border border-slate-300">Waktu Keluar</th>
                        <th class="p-2 border border-slate-300">Durasi</th>
                        <th class="p-2 border border-slate-300 text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $i => $l)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="p-2 border border-slate-300">{{ $i + 1 }}</td>
                            <td class="p-2 border border-slate-300 font-bold">{{ $l->no_plat }}</td>
                            <td class="p-2 border border-slate-300">{{ $l->tarif->jenis_kendaraan }}</td>
                            <td class="p-2 border border-slate-300">{{ $l->area->nama_area }}</td>
                            <td class="p-2 border border-slate-300">{{ $l->waktu_masuk }}</td>
                            <td class="p-2 border border-slate-300">{{ $l->waktu_keluar }}</td>
                            <td class="p-2 border border-slate-300">{{ $l->durasi_jam }} Jam</td>
                            <td class="p-2 border border-slate-300 text-right font-semibold">Rp {{ number_format($l->biaya_total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-4 text-center text-slate-500">Belum ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 font-bold text-slate-800">
                        <td colspan="7" class="p-2 border border-slate-300 text-right">TOTAL PENDAPATAN:</td>
                        <td class="p-2 border border-slate-300 text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection