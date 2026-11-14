@extends('layout.layout')

@section('content')
<h1 class="text-xl font-bold text-slate-800 mb-4">Dashboard Petugas</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Kendaraan Masuk (Belum Keluar)</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $parkirMasuk }} Unit</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Total Aktivitas Hari Ini</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $transaksiHariIni }}</div>
    </div>
</div>

<div class="bg-white border border-slate-300 rounded p-4">
    <h2 class="font-bold text-slate-700 mb-3 text-sm uppercase">Kapasitas Area Parkir</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($area as $a)
            <div class="border border-slate-200 p-3 rounded bg-slate-50">
                <div class="font-semibold text-slate-700">{{ $a->nama_area }}</div>
                <div class="text-xs text-slate-500 mt-1">Terisi: {{ $a->terisi }} / {{ $a->kapasitas }} Slot</div>
            </div>
        @endforeach
    </div>
</div>
@endsection