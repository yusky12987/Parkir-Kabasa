@extends('layout.layout')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Tambah Area Parkir Baru
    </div>
    <form action="{{ route('admin.area.store') }}" method="POST" class="p-4 space-y-3">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Area</label>
            <input type="text" name="nama_area" placeholder="Contoh: Gedung A / Lapangan Timut" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Kapasitas Maksimal (Slot)</label>
            <input type="number" name="kapasitas" placeholder="Contoh: 50" min="1" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.area.index') }}" class="text-xs text-slate-600 hover:underline">Batal</a>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection