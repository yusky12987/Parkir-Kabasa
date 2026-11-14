@extends('layout.layout')

@section('content')
<h1 class="text-xl font-bold text-slate-800 mb-4">Dashboard Owner</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Pendapatan Hari Ini</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Total Seluruh Pendapatan</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Total Kendaraan Selesai</div>
        <div class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTransaksi }} Transaksi</div>
    </div>
</div>
@endsection