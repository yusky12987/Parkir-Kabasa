@extends('layout.layout')

@section('content')
<h1 class="text-xl font-bold text-slate-800 mb-4">Dashboard Petugas</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Kendaraan Masuk (Belum Keluar)</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $parkirMasuk }} Unit</div>
    </div>
    <div class="bg-white border border-slate-300 p-4 rounded shadow-sm">
        <div class="text-xs font-semibold text-slate-500 uppercase">Total Aktivitas Hari Ini</div>
        <div class="text-3xl font-bold text-slate-800 mt-1">{{ $transaksiHariIni }}</div>
    </div>
</div>

<!-- Kapasitas Area Parkir (Klik untuk melihat daftar kendaraan di dalamnya) -->
<div class="bg-white border border-slate-300 rounded shadow-sm p-4">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-bold text-slate-700 text-sm uppercase">Kapasitas Area Parkir</h2>
        <span class="text-[11px] text-slate-500">Klik kotak area untuk melihat siapa saja yang parkir</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($area as $a)
            @php
                $penuh = $a->terisi >= $a->kapasitas;
            @endphp
            <button type="button"
                onclick="bukaDetailArea({{ $a->id_area }}, '{{ addslashes($a->nama_area) }}', {{ $a->terisi }}, {{ $a->kapasitas }})"
                class="text-left border p-3.5 rounded transition cursor-pointer group focus:outline-none focus:ring-2 focus:ring-slate-400
                {{ $penuh
                    ? 'bg-red-50/70 border-red-300 hover:bg-red-50'
                    : 'bg-slate-50 border-slate-200 hover:bg-slate-100 hover:border-slate-400' }}">

                <div class="flex justify-between items-start">
                    <div>
                        <div class="font-semibold text-slate-700 group-hover:text-slate-950">
                            {{ $a->nama_area }}
                        </div>
                        <div class="text-xs mt-1 {{ $penuh ? 'text-red-700 font-semibold' : 'text-slate-500' }}">
                            Terisi: {{ $a->terisi }} / {{ $a->kapasitas }} Slot
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if($penuh)
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-red-100 text-red-700 border border-red-300">
                                Penuh
                            </span>
                        @endif
                        <span class="text-[11px] font-semibold text-slate-500 group-hover:text-slate-800">
                            Lihat →
                        </span>
                    </div>
                </div>
            </button>
        @endforeach
    </div>
</div>

<!-- Modal Popup Daftar Kendaraan di Dalam Area -->
<div id="modal-area" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white border border-slate-300 rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
        <!-- Header Modal -->
        <div class="bg-slate-800 text-white px-4 py-3 flex justify-between items-center">
            <div>
                <h3 id="modal-judul-area" class="font-bold text-sm">Detail Area Parkir</h3>
                <p id="modal-subjudul-area" class="text-[11px] text-slate-300">Terisi: 0 / 0 Slot</p>
            </div>
            <button type="button" onclick="tutupModalArea()" class="text-slate-300 hover:text-white text-lg font-bold px-2">
                &times;
            </button>
        </div>

        <!-- Isi Tabel Kendaraan -->
        <div class="p-4 max-h-[65vh] overflow-y-auto">
            <div id="modal-konten-area"></div>
        </div>

        <!-- Footer Modal -->
        <div class="bg-slate-100 px-4 py-2.5 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="tutupModalArea()" class="bg-slate-700 hover:bg-slate-900 text-white px-4 py-1.5 rounded text-xs font-semibold transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const dataParkirPerArea = @json($parkirAktif ?? []);

    const modalArea    = document.getElementById('modal-area');
    const judulArea    = document.getElementById('modal-judul-area');
    const subjudulArea = document.getElementById('modal-subjudul-area');
    const kontenArea   = document.getElementById('modal-konten-area');

    function bukaDetailArea(idArea, namaArea, terisi, kapasitas) {
        judulArea.textContent    = 'Daftar Kendaraan di ' + namaArea;
        subjudulArea.textContent = `Kapasitas Terisi: ${terisi} / ${kapasitas} Slot`;

        const daftar = dataParkirPerArea[idArea] || [];

        if (daftar.length === 0) {
            kontenArea.innerHTML = `
                <div class="text-center py-8 text-slate-500 text-xs">
                    Belum ada kendaraan yang sedang parkir di <strong>${namaArea}</strong>.
                </div>
            `;
        } else {
            const baris = daftar.map((item, idx) => `
                <tr class="border-b border-slate-200 hover:bg-slate-50">
                    <td class="p-2 border border-slate-300 text-center text-slate-600">${idx + 1}</td>
                    <td class="p-2 border border-slate-300 font-bold text-slate-800">${item.no_plat}</td>
                    <td class="p-2 border border-slate-300">
                        ${item.is_member
                            ? `<span class="inline-block bg-emerald-100 text-emerald-800 border border-emerald-300 px-1.5 py-0.5 rounded text-[10px] font-semibold">Member • ${item.pemilik}</span>`
                            : `<span class="text-slate-600">Pengunjung Umum</span>`
                        }
                    </td>
                    <td class="p-2 border border-slate-300">${item.jenis}</td>
                    <td class="p-2 border border-slate-300">${item.waktu_masuk}</td>
                    <td class="p-2 border border-slate-300 text-center">
                        <a href="${item.url_keluar}" class="inline-block bg-slate-800 hover:bg-slate-950 text-white px-2.5 py-1 rounded text-[10px] font-semibold">
                            Keluar
                        </a>
                    </td>
                </tr>
            `).join('');

            kontenArea.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-slate-300 text-xs text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                                <th class="p-2 border border-slate-300 text-center w-10">No</th>
                                <th class="p-2 border border-slate-300">No. Plat</th>
                                <th class="p-2 border border-slate-300">Pemilik / Status</th>
                                <th class="p-2 border border-slate-300">Jenis</th>
                                <th class="p-2 border border-slate-300">Waktu Masuk</th>
                                <th class="p-2 border border-slate-300 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>${baris}</tbody>
                    </table>
                </div>
            `;
        }

        modalArea.classList.remove('hidden');
    }

    function tutupModalArea() {
        modalArea.classList.add('hidden');
    }

    modalArea.addEventListener('click', function (e) {
        if (e.target === modalArea) tutupModalArea();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modalArea.classList.contains('hidden')) {
            tutupModalArea();
        }
    });
</script>
@endsection