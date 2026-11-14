@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 flex justify-between items-center">
        <h2 class="font-bold text-slate-700">Data Langganan / Kendaraan Terdaftar</h2>
        <a href="{{ route('admin.kendaraan.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-1.5 rounded transition">
            + Tambah Kendaraan
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-xs text-left">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                    <th class="p-2 border border-slate-300">No</th>
                    <th class="p-2 border border-slate-300">No Plat</th>
                    <th class="p-2 border border-slate-300">Jenis</th>
                    <th class="p-2 border border-slate-300">Warna</th>
                    <th class="p-2 border border-slate-300">Pemilik</th>
                    <th class="p-2 border border-slate-300 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kendaraan as $i => $k)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300">{{ $i + 1 }}</td>
                        <td class="p-2 border border-slate-300 font-bold uppercase">{{ $k->no_plat }}</td>
                        <td class="p-2 border border-slate-300">{{ $k->jenis_kendaraan }}</td>
                        <td class="p-2 border border-slate-300">{{ $k->warna ?? '-' }}</td>
                        <td class="p-2 border border-slate-300">{{ $k->pemilik ?? '-' }}</td>
                        <td class="p-2 border border-slate-300 text-center flex justify-center gap-1">
                            <a href="{{ route('admin.kendaraan.edit', $k->id_kendaraan) }}" class="border border-slate-400 px-2 py-1 rounded hover:bg-slate-200 text-[10px]">Edit</a>
                            <form action="{{ route('admin.kendaraan.destroy', $k->id_kendaraan) }}" method="POST" onsubmit="return confirm('Hapus data kendaraan ini?')" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1 rounded text-[10px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-500">Belum ada data kendaraan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection