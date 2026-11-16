@extends('layout.layout')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Panel Kiri: Form Input Kendaraan Masuk (7 Kolom) -->
    <div class="lg:col-span-7 bg-white border border-slate-300 rounded shadow-sm h-fit">
        <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700 flex justify-between items-center">
            <span>Input Kendaraan Masuk</span>
            <span class="text-[11px] font-normal bg-slate-300/70 text-slate-700 px-2 py-0.5 rounded">
                Shortcut: F1 / F2 Pilih Jenis • Enter Simpan
            </span>
        </div>

        <div class="p-4">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-3 py-2 rounded text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <div id="js-alert" class="hidden mb-4 bg-amber-50 border border-amber-300 text-amber-800 px-3 py-2 rounded text-xs">
                Silakan pilih jenis kendaraan (Motor / Mobil) terlebih dahulu.
            </div>

            <form id="form-masuk" action="{{ route('petugas.transaksi.masuk.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Nomor Plat dengan Custom Smart Dropdown -->
                <div class="relative">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                        Nomor Plat Kendaraan
                    </label>
                    <input type="text" id="no_plat" name="no_plat" value="{{ old('no_plat') }}"
                        autocomplete="off" autofocus required
                        placeholder="Ketik plat nomor (contoh: B 1234 ABC)..."
                        class="w-full border border-slate-300 rounded px-3 py-2.5 text-base font-bold text-slate-800 uppercase placeholder:normal-case placeholder:font-normal placeholder:text-slate-400 focus:outline-none focus:border-slate-700">

                    <!-- Custom Dropdown Saran (Muncul hanya saat mengetik) -->
                    <div id="custom-dropdown" class="hidden absolute z-30 left-0 right-0 mt-1 bg-white border border-slate-300 rounded-md shadow-lg max-h-60 overflow-y-auto divide-y divide-slate-100">
                    </div>

                    <!-- Indikator Status Pengenalan Otomatis -->
                    <div id="info-kendaraan" class="mt-1.5 text-xs text-slate-500">
                        Ketik plat nomor—kendaraan lama & member otomatis mengisi jenis serta area.
                    </div>
                </div>

                <!-- Pilihan Cepat Jenis Kendaraan (1-Klik / Otomatis Terisi) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1.5">
                        Jenis Kendaraan & Tarif
                    </label>
                    <input type="hidden" id="id_tarif" name="id_tarif" value="{{ old('id_tarif') }}">

                    <div class="grid grid-cols-2 gap-2.5">
                        @foreach($tarif as $idx => $t)
                            <button type="button"
                                data-id="{{ $t->id_tarif }}"
                                data-nama="{{ $t->jenis_kendaraan }}"
                                onclick="pilihTarif('{{ $t->id_tarif }}', true)"
                                class="btn-tarif border-2 border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg p-3 text-left transition flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-sm">{{ $t->jenis_kendaraan }}</div>
                                    <div class="text-[11px] opacity-80">Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }} / jam</div>
                                </div>
                                <span class="badge-key text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-200 text-slate-600">
                                    F{{ $idx + 1 }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Pilihan Area Parkir -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                        Area Parkir
                    </label>
                    <select id="id_area" name="id_area" required
                        class="w-full border border-slate-300 rounded px-3 py-2 text-sm text-slate-800 focus:outline-none focus:border-slate-700">
                        <option value="">-- Pilih Area Parkir --</option>
                        @foreach($area as $a)
                            @php $penuh = $a->terisi >= $a->kapasitas; @endphp
                            <option value="{{ $a->id_area }}" data-nama="{{ strtolower($a->nama_area) }}" {{ $penuh ? 'disabled' : '' }} {{ old('id_area') == $a->id_area ? 'selected' : '' }}>
                                {{ $a->nama_area }} (Terisi: {{ $a->terisi }}/{{ $a->kapasitas }}) {{ $penuh ? '- PENUH' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btn-submit" class="w-full bg-slate-800 hover:bg-slate-900 focus:ring-2 focus:ring-slate-400 text-white font-semibold py-2.5 rounded text-sm transition">
                        Simpan & Cetak Tiket Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel Kanan: Akses Cepat Member & Kendaraan Rutin (5 Kolom) -->
    <div class="lg:col-span-5 bg-white border border-slate-300 rounded shadow-sm h-fit">
        <div class="bg-slate-200 px-4 py-3 border-b border-slate-300 font-bold text-slate-700 flex justify-between items-center">
            <span>Akses Cepat Member & Rutin</span>
            <span class="text-[11px] font-normal text-slate-600">1-Klik Isi Otomatis</span>
        </div>

        <div class="p-4">
            @php
                $listCepat = collect($databasePlat)->sortByDesc(function ($item) {
                    return ($item['is_member'] ? 10000 : 0) + $item['kunjungan'];
                })->take(8);
            @endphp

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs text-left">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-300 text-slate-700">
                            <th class="p-2 border border-slate-300">No. Plat</th>
                            <th class="p-2 border border-slate-300">Jenis</th>
                            <th class="p-2 border border-slate-300">Status</th>
                            <th class="p-2 border border-slate-300 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listCepat as $item)
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="p-2 border border-slate-300 font-bold text-slate-800">
                                    {{ $item['no_plat'] }}
                                </td>
                                <td class="p-2 border border-slate-300 text-slate-700 font-medium">
                                    {{ $item['nama_tarif'] ?? '-' }}
                                </td>
                                <td class="p-2 border border-slate-300">
                                    @if($item['is_member'])
                                        <span class="inline-block bg-emerald-100 text-emerald-800 border border-emerald-300 px-1.5 py-0.5 rounded text-[10px] font-semibold">
                                            Member • {{ $item['pemilik'] }}
                                        </span>
                                    @else
                                        <span class="text-slate-600 text-[11px]">
                                            Parkir {{ $item['kunjungan'] }}x
                                        </span>
                                    @endif
                                </td>
                                <td class="p-2 border border-slate-300 text-center">
                                    <button type="button"
                                        onclick="pilihDariTabel('{{ $item['no_plat'] }}')"
                                        class="bg-slate-700 hover:bg-slate-900 text-white px-2.5 py-1 rounded text-[11px] font-semibold transition">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-slate-500">
                                    Belum ada riwayat kendaraan atau member.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
    const databasePlat  = @json($databasePlat);
    const listKendaraan = Object.values(databasePlat);

    const formMasuk   = document.getElementById('form-masuk');
    const inputPlat   = document.getElementById('no_plat');
    const dropdown    = document.getElementById('custom-dropdown');
    const inputTarif  = document.getElementById('id_tarif');
    const selectArea  = document.getElementById('id_area');
    const infoBox     = document.getElementById('info-kendaraan');
    const btnSubmit   = document.getElementById('btn-submit');
    const jsAlert     = document.getElementById('js-alert');
    const btnTarifEls = document.querySelectorAll('.btn-tarif');

    let hasilFilter = [];
    let indexAktif  = -1;

    function pilihAreaTersedia(preferIdArea, namaJenis = '') {
        // 1. Jika ada riwayat area sebelumnya dan belum penuh, pakai area tersebut
        if (preferIdArea) {
            const opsiPrefer = selectArea.querySelector(`option[value="${preferIdArea}"]`);
            if (opsiPrefer && !opsiPrefer.disabled) {
                selectArea.value = preferIdArea;
                return;
            }
        }
        // 2. Cari area yang namanya cocok dengan jenis kendaraan (misal "Area Motor" / "Area Mobil")
        const jenisLower = namaJenis.toLowerCase();
        if (jenisLower) {
            const opsiCocokJenis = Array.from(selectArea.options).find(opt =>
                opt.value !== '' && !opt.disabled && (opt.dataset.nama || '').includes(jenisLower)
            );
            if (opsiCocokJenis) {
                selectArea.value = opsiCocokJenis.value;
                return;
            }
        }
        // 3. Fallback ke area pertama yang masih tersedia
        const opsiKosong = Array.from(selectArea.options).find(opt => opt.value !== '' && !opt.disabled);
        if (opsiKosong) {
            selectArea.value = opsiKosong.value;
        }
    }

    function pilihTarif(idTarif, autoSetArea = false) {
        inputTarif.value = idTarif || '';
        jsAlert.classList.add('hidden');
        let namaJenisTerpilih = '';

        btnTarifEls.forEach(btn => {
            const aktif = String(btn.dataset.id) === String(idTarif);
            const badge = btn.querySelector('.badge-key');
            if (aktif) {
                namaJenisTerpilih = btn.dataset.nama || '';
                btn.className = 'btn-tarif border-2 border-slate-800 bg-slate-800 text-white rounded-lg p-3 text-left transition flex justify-between items-center shadow-sm';
                if (badge) badge.className = 'badge-key text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-700 text-slate-200';
            } else {
                btn.className = 'btn-tarif border-2 border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg p-3 text-left transition flex justify-between items-center';
                if (badge) badge.className = 'badge-key text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-200 text-slate-600';
            }
        });

        if (autoSetArea && namaJenisTerpilih && !selectArea.value) {
            pilihAreaTersedia(null, namaJenisTerpilih);
        }
    }

    function terapkanDataKendaraan(data) {
        inputPlat.value = data.no_plat;
        pilihTarif(data.id_tarif, false);
        pilihAreaTersedia(data.id_area, data.nama_tarif || '');

        const labelJenis = data.nama_tarif ? ` (${data.nama_tarif})` : '';
        if (data.is_member) {
            infoBox.innerHTML = `<span class="inline-block bg-emerald-100 text-emerald-800 border border-emerald-300 font-semibold px-2 py-0.5 rounded">✓ Member ${data.pemilik}${labelJenis} — Otomatis terisi</span>`;
        } else {
            infoBox.innerHTML = `<span class="inline-block bg-sky-100 text-sky-800 border border-sky-300 font-semibold px-2 py-0.5 rounded">↻ Pernah parkir ${data.kunjungan}x${labelJenis} — Otomatis terisi</span>`;
        }
        tutupDropdown();
    }

    function pilihDariTabel(noPlat) {
        const data = databasePlat[noPlat];
        if (data) {
            terapkanDataKendaraan(data);
            btnSubmit.focus();
        }
    }

    function tutupDropdown() {
        dropdown.classList.add('hidden');
        dropdown.innerHTML = '';
        hasilFilter = [];
        indexAktif = -1;
    }

    function renderDropdown() {
        const rawQuery   = inputPlat.value.toUpperCase().trim();
        const cleanQuery = rawQuery.replace(/\s+/g, '');

        if (cleanQuery.length === 0) {
            tutupDropdown();
            infoBox.innerHTML = `Ketik plat nomor—kendaraan lama & member otomatis mengisi jenis serta area.`;
            return;
        }

        const exactMatch = listKendaraan.find(item => item.no_plat.replace(/\s+/g, '') === cleanQuery);
        if (exactMatch) {
            terapkanDataKendaraan(exactMatch);
            return;
        }

        hasilFilter = listKendaraan.filter(item =>
            item.no_plat.replace(/\s+/g, '').includes(cleanQuery)
        ).slice(0, 5);

        if (hasilFilter.length === 0) {
            tutupDropdown();
            if (cleanQuery.length >= 3) {
                infoBox.innerHTML = `<span class="text-slate-600 font-medium">Kendaraan baru — klik <strong>Motor / Mobil</strong> (atau tekan F1 / F2).</span>`;
            }
            return;
        }

        indexAktif = -1;
        dropdown.innerHTML = hasilFilter.map((item, idx) => `
            <button type="button" data-idx="${idx}"
                class="item-saran w-full text-left px-3 py-2 flex justify-between items-center hover:bg-slate-100 transition text-xs">
                <div>
                    <span class="font-bold text-slate-800">${item.no_plat}</span>
                    ${item.nama_tarif ? `<span class="ml-1.5 text-[11px] text-slate-500">• ${item.nama_tarif}</span>` : ''}
                </div>
                ${item.is_member
                    ? `<span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-semibold">Member • ${item.pemilik}</span>`
                    : `<span class="text-slate-500 text-[11px]">Parkir ${item.kunjungan}x</span>`
                }
            </button>
        `).join('');

        dropdown.classList.remove('hidden');

        dropdown.querySelectorAll('.item-saran').forEach(btn => {
            btn.addEventListener('click', function () {
                const dipilih = hasilFilter[parseInt(this.dataset.idx)];
                if (dipilih) {
                    terapkanDataKendaraan(dipilih);
                    btnSubmit.focus();
                }
            });
        });
    }

    inputPlat.addEventListener('input', renderDropdown);

    inputPlat.addEventListener('keydown', function (e) {
        if (dropdown.classList.contains('hidden') || hasilFilter.length === 0) return;

        const items = dropdown.querySelectorAll('.item-saran');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            indexAktif = (indexAktif + 1) % items.length;
            items.forEach((el, i) => el.classList.toggle('bg-slate-100', i === indexAktif));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            indexAktif = (indexAktif - 1 + items.length) % items.length;
            items.forEach((el, i) => el.classList.toggle('bg-slate-100', i === indexAktif));
        } else if (e.key === 'Enter' && indexAktif >= 0) {
            e.preventDefault();
            terapkanDataKendaraan(hasilFilter[indexAktif]);
            btnSubmit.focus();
        } else if (e.key === 'Escape') {
            tutupDropdown();
        }
    });

    // Shortcut F1, F2, dst. untuk memilih jenis kendaraan dengan cepat
    document.addEventListener('keydown', function (e) {
        if (e.key === 'F1' || e.key === 'F2' || e.key === 'F3' || e.key === 'F4') {
            const idx = parseInt(e.key.replace('F', '')) - 1;
            if (btnTarifEls[idx]) {
                e.preventDefault();
                pilihTarif(btnTarifEls[idx].dataset.id, true);
            }
        }
    });

    formMasuk.addEventListener('submit', function (e) {
        if (!inputTarif.value) {
            e.preventDefault();
            jsAlert.classList.remove('hidden');
        }
    });

    document.addEventListener('click', function (e) {
        if (!inputPlat.contains(e.target) && !dropdown.contains(e.target)) {
            tutupDropdown();
        }
    });

    // Pulihkan pilihan jika terjadi validasi ulang (old input)
    if (inputTarif.value) {
        pilihTarif(inputTarif.value, false);
    }
</script>
@endsection