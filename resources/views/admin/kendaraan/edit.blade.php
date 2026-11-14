@extends('layout.layout')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Edit Data Kendaraan
    </div>
    <form action="{{ route('admin.kendaraan.update', $kendaraan->id_kendaraan) }}" method="POST" class="p-4 space-y-3">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Plat</label>
            <input type="text" name="no_plat" value="{{ $kendaraan->no_plat }}" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600 uppercase">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Kendaraan</label>
            <input type="text" name="jenis_kendaraan" value="{{ $kendaraan->jenis_kendaraan }}" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Warna Kendaraan</label>
            <input type="text" name="warna" value="{{ $kendaraan->warna }}" class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Pemilik</label>
            <input type="text" name="pemilik" value="{{ $kendaraan->pemilik }}" class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.kendaraan.index') }}" class="text-xs text-slate-600 hover:underline">Batal</a>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection