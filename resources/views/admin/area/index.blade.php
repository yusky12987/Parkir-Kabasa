@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 flex justify-between items-center">
        <h2 class="font-bold text-slate-700">Data Area Parkir</h2>
        <a href="{{ route('admin.area.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-1.5 rounded transition">
            + Tambah Area
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-xs text-left">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                    <th class="p-2 border border-slate-300">No</th>
                    <th class="p-2 border border-slate-300">Nama Area</th>
                    <th class="p-2 border border-slate-300">Kapasitas</th>
                    <th class="p-2 border border-slate-300">Terisi</th>
                    <th class="p-2 border border-slate-300 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($area as $i => $a)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300">{{ $i + 1 }}</td>
                        <td class="p-2 border border-slate-300 font-semibold">{{ $a->nama_area }}</td>
                        <td class="p-2 border border-slate-300">{{ $a->kapasitas }} Slot</td>
                        <td class="p-2 border border-slate-300">{{ $a->terisi }} Slot</td>
                        <td class="p-2 border border-slate-300 text-center flex justify-center gap-1">
                            <a href="{{ route('admin.area.edit', $a->id_area) }}" class="border border-slate-400 px-2 py-1 rounded hover:bg-slate-200 text-[10px]">Edit</a>
                            <form action="{{ route('admin.area.destroy', $a->id_area) }}" method="POST" onsubmit="return confirm('Hapus area ini?')" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1 rounded text-[10px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-slate-500">Belum ada data area parkir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection