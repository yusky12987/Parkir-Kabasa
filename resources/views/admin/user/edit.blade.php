@extends('layout.layout')

@section('content')
<div class="max-w-md mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Edit Data User
    </div>
    <form action="{{ route('admin.user.update', $user->id_user) }}" method="POST" class="p-4 space-y-3">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Username</label>
            <input type="text" name="username" value="{{ $user->username }}" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Password Baru <span class="text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password" placeholder="••••••••" class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Role</label>
            <select name="role" required class="w-full border border-slate-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-slate-600">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
            </select>
        </div>
        
        <div class="flex justify-between items-center pt-2">
            <a href="{{ route('admin.user.index') }}" class="text-xs text-slate-600 hover:underline">Batal</a>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection