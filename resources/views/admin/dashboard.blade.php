@extends('layout.layout')

@section('content')
<h1 class="text-xl font-bold text-slate-800 mb-4">Dashboard Admin</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Total User</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalUser }}</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Tarif Parkir</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTarif }} Jenis</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Area Parkir</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalArea }} Lokasi</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Transaksi Hari Ini</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $transaksiHariIni }}</div>
    </div>
</div>
@endsection