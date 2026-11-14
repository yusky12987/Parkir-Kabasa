@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 flex justify-between items-center">
        <h2 class="font-bold text-slate-700">Data User / Pengguna</h2>
        <a href="{{ route('admin.user.create') }}" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-3 py-1.5 rounded transition">
            + Tambah User
        </a>
    </div>

    <div class="p-4 overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-xs text-left">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                    <th class="p-2 border border-slate-300">No</th>
                    <th class="p-2 border border-slate-300">Nama Lengkap</th>
                    <th class="p-2 border border-slate-300">Username</th>
                    <th class="p-2 border border-slate-300">Role</th>
                    <th class="p-2 border border-slate-300 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $u)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300">{{ $i + 1 }}</td>
                        <td class="p-2 border border-slate-300 font-semibold">{{ $u->nama_lengkap }}</td>
                        <td class="p-2 border border-slate-300">{{ $u->username }}</td>
                        <td class="p-2 border border-slate-300 uppercase"><span class="bg-slate-200 px-2 py-0.5 rounded border border-slate-300 text-[10px]">{{ $u->role }}</span></td>
                        <td class="p-2 border border-slate-300 text-center flex justify-center gap-1">
                            <a href="{{ route('admin.user.edit', $u->id_user) }}" class="border border-slate-400 px-2 py-1 rounded hover:bg-slate-200 text-[10px]">Edit</a>
                            <form action="{{ route('admin.user.destroy', $u->id_user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-2 py-1 rounded text-[10px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection