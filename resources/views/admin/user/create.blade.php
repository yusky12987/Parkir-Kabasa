@extends('layout.layout')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Tambah User Baru
    </div>
    <form action="{{ route('admin.user.store') }}" method="POST" class="p-4 space-y-3">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Username</label>
            <input type="text" name="username" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
            <input type="password" name="password" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Role</label>
            <select name="role" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="owner">Owner</option>
            </select>
        </div>
        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.user.index') }}" class="text-xs text-slate-600 hover:underline">Batal</a>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection