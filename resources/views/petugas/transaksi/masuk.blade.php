@extends('layout.layout')

@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-300 rounded shadow-sm">
    <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700 flex justify-between items-center">
        <span>Input Kendaraan Masuk</span>
        <span id="status-badge" class="hidden text-xs font-semibold px-2.5 py-0.5 rounded"></span>
    </div>

    @if($errors->any())
        <div class="m-4 bg-slate-100 border border-slate-400 text-slate-700 px-3 py-2 rounded text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('petugas.transaksi.masuk.store') }}" method="POST" class="p-4 space-y-4">
        @csrf
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-xs font-semibold text-slate-600 uppercase">Nomor Plat Kendaraan</label>
                <span class="text-[11px] text-slate-500">Pilih langganan atau ketik plat pengunjung umum</span>
            </div>
            <input type="text" id="no_plat" name="no_plat" list="daftar-kendaraan"
                placeholder="Contoh: Q 1212 N atau B 1234 ABC" required autofocus autocomplete="off"
                class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600 uppercase">

            <datalist id="daftar-kendaraan">
                @foreach($kendaraan as $k)
                    <option value="{{ $k->no_plat }}">
                        {{ $k->jenis_kendaraan }} - {{ $k->pemilik }} ({{ $k->warna }})
                    </option>
                @endforeach
            </datalist>
        </div>

        <!-- Info otomatis jika plat terdaftar di Master Admin -->
        <div id="info-terdaftar" class="hidden bg-emerald-50 border border-emerald-200 rounded p-3 text-xs text-emerald-800">
            <div class="font-semibold mb-1">Data Kendaraan Terdaftar (Langganan):</div>
            <div class="grid grid-cols-2 gap-2">
                <div>Pemilik: <strong id="info-pemilik">-</strong></div>
                <div>Warna: <strong id="info-warna">-</strong></div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Jenis Kendaraan / Tarif</label>
            <select name="id_tarif" id="id_tarif" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
                <option value="">-- Pilih Jenis --</option>
                @foreach($tarif as $t)
                    <option value="{{ $t->id_tarif }}" data-jenis="{{ strtolower(trim($t->jenis_kendaraan)) }}">
                        {{ $t->jenis_kendaraan }} - Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}/jam
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Area Parkir</label>
            <select name="id_area" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-slate-600">
                <option value="">-- Pilih Area --</option>
                @foreach($area as $a)
                    <option value="{{ $a->id_area }}">{{ $a->nama_area }} (Terisi: {{ $a->terisi }}/{{ $a->kapasitas }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 rounded text-sm transition">
            Simpan & Cetak Tiket
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataKendaraan = @json($kendaraan);

        const inputPlat = document.getElementById('no_plat');
        const selectTarif = document.getElementById('id_tarif');
        const statusBadge = document.getElementById('status-badge');
        const infoTerdaftar = document.getElementById('info-terdaftar');
        const infoPemilik = document.getElementById('info-pemilik');
        const infoWarna = document.getElementById('info-warna');

        function cekPlat() {
            const ketikan = inputPlat.value.toUpperCase().trim();

            if (!ketikan) {
                statusBadge.classList.add('hidden');
                infoTerdaftar.classList.add('hidden');
                return;
            }

            const cocok = dataKendaraan.find(item => item.no_plat.toUpperCase().trim() === ketikan);

            if (cocok) {
                statusBadge.textContent = '✓ Kendaraan Langganan';
                statusBadge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300';

                infoPemilik.textContent = cocok.pemilik || '-';
                infoWarna.textContent = cocok.warna || '-';
                infoTerdaftar.classList.remove('hidden');

                const jenisTarget = (cocok.jenis_kendaraan || '').toLowerCase().trim();
                for (let option of selectTarif.options) {
                    if (option.dataset.jenis === jenisTarget) {
                        selectTarif.value = option.value;
                        break;
                    }
                }
            } else {
                statusBadge.textContent = 'Pengunjung Umum';
                statusBadge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-300';
                infoTerdaftar.classList.add('hidden');
            }
        }

        inputPlat.addEventListener('input', cekPlat);
        inputPlat.addEventListener('change', cekPlat);
    });
</script>
@endsection