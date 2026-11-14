@extends('layout.layout')

@section('content')
<div class="bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700">
        Log Aktivitas Sistem
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full border-collapse border border-slate-300 text-xs text-left">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                    <th class="p-2 border border-slate-300">Waktu</th>
                    <th class="p-2 border border-slate-300">User</th>
                    <th class="p-2 border border-slate-300">Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $l)
                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300">{{ $l->waktu_aktivitas }}</td>
                        <td class="p-2 border border-slate-300 font-semibold">{{ $l->user->nama_lengkap ?? 'System' }}</td>
                        <td class="p-2 border border-slate-300">{{ $l->aktivitas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection