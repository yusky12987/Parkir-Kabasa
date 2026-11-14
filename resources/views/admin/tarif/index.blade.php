@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 flex justify-between items-center">
        <h2 class="font-bold text-slate-700">Data Tarif Parkir</h2>
        <a href="{{ route('admin.tarif.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-1.5 rounded transition">
            + Tambah Tarif
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-xs text-left">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                    <th class="p-2 border border-slate-300">No</th>
                    <th class="p-2 border border-slate-300">Jenis Kendaraan</th>
                    <th class="p-2 border border-slate-300">Tarif / Jam</th>
                    <th class="p-2 border border-slate-300 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarif as $i => $t)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300">{{ $i + 1 }}</td>
                        <td class="p-2 border border-slate-300 font-semibold">{{ $t->jenis_kendaraan }}</td>
                        <td class="p-2 border border-slate-300">Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</td>
                        <td class="p-2 border border-slate-300 text-center flex justify-center gap-1">
                            <a href="{{ route('admin.tarif.edit', $t->id_tarif) }}" class="border border-slate-400 px-2 py-1 rounded hover:bg-slate-200 text-[10px]">Edit</a>
                            <form action="{{ route('admin.tarif.destroy', $t->id_tarif) }}" method="POST" onsubmit="return confirm('Hapus tarif ini?')" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1 rounded text-[10px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-slate-500">Belum ada data tarif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection