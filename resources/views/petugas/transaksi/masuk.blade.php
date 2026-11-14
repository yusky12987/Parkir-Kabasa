@extends('layout.layout')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Input Kendaraan Masuk
    </div>

    @if($errors->any())
        <div class="m-4 bg-slate-100 border border-slate-400 text-slate-700 px-3 py-2 rounded text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('petugas.transaksi.masuk.store') }}" method="POST" class="p-4 space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nomor Plat Kendaraan</label>
            <input type="text" name="no_plat" placeholder="Contoh: B 1234 ABC" required autofocus
                class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600 uppercase">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Jenis Kendaraan / Tarif</label>
            <select name="id_tarif" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
                <option value="">-- Pilih Jenis --</option>
                @foreach($tarif as $t)
                    <option value="{{ $t->id_tarif }}">{{ $t->jenis_kendaraan }} - Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}/jam</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Area Parkir</label>
            <select name="id_area" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
                <option value="">-- Pilih Area --</option>
                @foreach($area as $a)
                    <option value="{{ $a->id_area }}">{{ $a->nama_area }} (Terisi: {{ $a->terisi }}/{{ $a->kapasitas }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 rounded text-sm transition">
            Simpan & Cetak Tiket
        </button>
    </form>
</div>
@endsection