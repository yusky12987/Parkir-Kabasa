<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Tarif;
use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TransaksiController extends Controller
{
    // 1. Tampilan Input Kendaraan Masuk (Scan RFID + Smart Auto-Fill Riwayat & Member)
    public function masuk()
    {
        $tarif = Tarif::all();
        $area = AreaParkir::all();
        $tarifMap = $tarif->keyBy('id_tarif');

        // Ambil riwayat kendaraan yang pernah parkir dari tb_transaksi
        $riwayatTransaksi = Transaksi::select('no_plat', 'id_tarif', 'id_area')
            ->orderBy('id_parkir', 'desc')
            ->get()
            ->groupBy('no_plat');

        $databasePlat = [];

        foreach ($riwayatTransaksi as $plat => $items) {
            $terbaru = $items->first();
            $databasePlat[$plat] = [
                'no_plat'    => $plat,
                'id_tarif'   => $terbaru->id_tarif,
                'nama_tarif' => $tarifMap[$terbaru->id_tarif]->jenis_kendaraan ?? null,
                'id_area'    => $terbaru->id_area,
                'kunjungan'  => $items->count(),
                'is_member'  => false,
                'pemilik'    => null,
            ];
        }

        // Gabungkan dengan data Member Langganan dari tb_kendaraan & petakan UID RFID
        $members = Kendaraan::orderBy('no_plat', 'asc')->get();
        $rfidMap = [];
        $hasRfidColumn = Schema::hasColumn('tb_kendaraan', 'rfid_uid');

        foreach ($members as $m) {
            $plat = strtoupper(trim($m->no_plat));
            $tarifId = $m->id_tarif ?? null;

            // Cocokkan jenis kendaraan member secara fleksibel (abaikan huruf besar/kecil)
            if (!$tarifId) {
                $jenisMember = strtolower(trim($m->jenis_kendaraan ?? $m->jenis ?? $m->tipe_kendaraan ?? ''));
                if ($jenisMember !== '') {
                    $cocok = $tarif->first(function ($t) use ($jenisMember) {
                        $namaTarif = strtolower(trim($t->jenis_kendaraan));
                        return $namaTarif === $jenisMember
                            || str_contains($namaTarif, $jenisMember)
                            || str_contains($jenisMember, $namaTarif);
                    });
                    $tarifId = $cocok ? $cocok->id_tarif : null;
                }
            }

            $namaTarif = $tarifId ? ($tarifMap[$tarifId]->jenis_kendaraan ?? null) : null;

            if (isset($databasePlat[$plat])) {
                $databasePlat[$plat]['is_member'] = true;
                $databasePlat[$plat]['pemilik']   = $m->pemilik ?? 'Member';
                if ($tarifId) {
                    $databasePlat[$plat]['id_tarif']   = $tarifId;
                    $databasePlat[$plat]['nama_tarif'] = $namaTarif;
                }
            } else {
                $databasePlat[$plat] = [
                    'no_plat'    => $plat,
                    'id_tarif'   => $tarifId,
                    'nama_tarif' => $namaTarif,
                    'id_area'    => null,
                    'kunjungan'  => 0,
                    'is_member'  => true,
                    'pemilik'    => $m->pemilik ?? 'Member',
                ];
            }

            if ($hasRfidColumn && !empty($m->rfid_uid)) {
                $rfidMap[trim($m->rfid_uid)] = $databasePlat[$plat];
            }
        }

        return view('petugas.transaksi.masuk', compact('tarif', 'area', 'databasePlat', 'rfidMap'));
    }

    // Proses Simpan Kendaraan Masuk (+ Registrasi Otomatis jika Kartu RFID Baru)
    public function storeMasuk(Request $request)
    {
        $request->validate([
            'no_plat'  => 'required|string|max:15',
            'id_tarif' => 'required|exists:tb_tarif,id_tarif',
            'id_area'  => 'required|exists:tb_area_parkir,id_area',
            'rfid_uid' => 'nullable|string|max:50',
            'pemilik'  => 'nullable|string|max:100',
        ]);

        $noPlat = strtoupper(trim($request->no_plat));

        // Validasi Standar Industri: Cegah kendaraan yang masih di dalam masuk 2 kali
        $masihParkir = Transaksi::where('no_plat', $noPlat)
            ->where('status', 'Masuk')
            ->exists();

        if ($masihParkir) {
            return back()
                ->withErrors(['no_plat' => "Kendaraan dengan plat {$noPlat} masih tercatat sedang parkir di dalam!"])
                ->withInput();
        }

        $area = AreaParkir::findOrFail($request->id_area);
        if ($area->terisi >= $area->kapasitas) {
            return back()->withErrors(['id_area' => 'Area parkir ini sudah penuh!'])->withInput();
        }

        // Registrasi otomatis kartu RFID baru ke tb_kendaraan
        $pesanTambahan = '';
        if ($request->filled('rfid_uid') && Schema::hasColumn('tb_kendaraan', 'rfid_uid')) {
            $uid = trim($request->rfid_uid);
            $sudahAdaRfid = Kendaraan::where('rfid_uid', $uid)->first();

            if (!$sudahAdaRfid) {
                $tarifObj = Tarif::find($request->id_tarif);
                $dataMember = [
                    'rfid_uid' => $uid,
                    'no_plat'  => $noPlat,
                    'pemilik'  => $request->filled('pemilik') ? trim($request->pemilik) : 'Member KTS',
                ];

                if (Schema::hasColumn('tb_kendaraan', 'jenis_kendaraan')) {
                    $dataMember['jenis_kendaraan'] = $tarifObj ? $tarifObj->jenis_kendaraan : 'Motor';
                }
                if (Schema::hasColumn('tb_kendaraan', 'id_tarif')) {
                    $dataMember['id_tarif'] = $request->id_tarif;
                }

                Kendaraan::create($dataMember);
                LogAktivitas::catat(Auth::user()->id_user, "Registrasi Member RFID Baru: {$noPlat} (UID: {$uid})");
                $pesanTambahan = ' & kartu RFID baru berhasil didaftarkan!';
            }
        }

        $transaksi = Transaksi::create([
            'no_plat'     => $noPlat,
            'waktu_masuk' => Carbon::now(),
            'id_tarif'    => $request->id_tarif,
            'id_area'     => $request->id_area,
            'id_user'     => Auth::user()->id_user,
            'status'      => 'Masuk'
        ]);

        // Catat Log
        LogAktivitas::catat(Auth::user()->id_user, "Kendaraan Masuk: " . $transaksi->no_plat);

        return redirect()->route('petugas.transaksi.cetak', $transaksi->id_parkir)
                         ->with('success', 'Transaksi masuk berhasil dicatat' . $pesanTambahan);
    }

    // 2. Tampilan Transaksi Keluar & Kasir Bayar (Mendukung Pencarian Plat & Tap RFID)
    public function keluar(Request $request)
    {
        $transaksi = null;
        $daftarParkir = Transaksi::with(['tarif', 'area'])
            ->where('status', 'Masuk')
            ->latest('id_parkir')
            ->get();

        if ($request->has('no_plat') && $request->no_plat != '') {
            $inputCari = trim($request->no_plat);
            $platDicari = strtoupper($inputCari);

            // Jika petugas/siswa menempelkan kartu RFID di kolom pencarian keluar
            if (Schema::hasColumn('tb_kendaraan', 'rfid_uid')) {
                $memberRfid = Kendaraan::where('rfid_uid', $inputCari)->first();
                if ($memberRfid) {
                    $platDicari = strtoupper(trim($memberRfid->no_plat));
                }
            }

            $transaksi = Transaksi::with(['tarif', 'area'])
                ->where('no_plat', $platDicari)
                ->where('status', 'Masuk')
                ->latest('id_parkir')
                ->first();

            if ($transaksi) {
                $masuk = Carbon::parse($transaksi->waktu_masuk);
                $keluar = Carbon::now();

                // Hitung durasi jam (minimal 1 jam, pembulatan ke atas)
                $durasi = max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));
                $biaya  = $durasi * $transaksi->tarif->tarif_per_jam;

                $transaksi->waktu_keluar_temp = $keluar->format('Y-m-d H:i:s');
                $transaksi->durasi_temp       = $durasi;
                $transaksi->biaya_temp        = $biaya;
            }
        }

        return view('petugas.transaksi.keluar', compact('transaksi', 'daftarParkir'));
    }

    // Proses Pembayaran Keluar
    public function storeKeluar(Request $request, $id)
    {
        $transaksi = Transaksi::with('tarif')->findOrFail($id);

        if ($transaksi->status == 'Keluar') {
            return redirect()->route('petugas.transaksi.cetak.keluar', $transaksi->id_parkir);
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:Tunai,QRIS',
            'uang_bayar'        => 'required|numeric|min:0',
        ]);

        $masuk  = Carbon::parse($transaksi->waktu_masuk);
        $keluar = Carbon::now();
        $durasi = max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));

        // Cek apakah kendaraan terdaftar sebagai Member Langganan di tb_kendaraan
        $member = Kendaraan::where('no_plat', $transaksi->no_plat)->first();
        $biaya  = $member ? 0 : ($durasi * $transaksi->tarif->tarif_per_jam);

        // Jika QRIS otomatis uang pas sesuai total tagihan
        $uangBayar = $request->metode_pembayaran === 'QRIS' ? $biaya : (float) $request->uang_bayar;

        if ($uangBayar < $biaya) {
            return back()->withErrors(['uang_bayar' => 'Uang pembayaran kurang dari total tagihan!']);
        }

        $kembalian = $uangBayar - $biaya;

        $transaksi->update([
            'waktu_keluar'      => $keluar,
            'durasi_jam'        => $durasi,
            'biaya_total'       => $biaya,
            'metode_pembayaran' => $request->metode_pembayaran,
            'uang_bayar'        => $uangBayar,
            'kembalian'         => $kembalian,
            'status'            => 'Keluar'
        ]);

        // Kurangi jumlah terisi (sudah otomatis lewat Trigger MySQL trg_kendaraan_keluar)
        // AreaParkir::where('id_area', $transaksi->id_area)->decrement('terisi');

        // Catat Log
        LogAktivitas::catat(
            Auth::user()->id_user,
            "Kendaraan Keluar: {$transaksi->no_plat} | Total: Rp " . number_format($biaya, 0, ',', '.')
        );

        return redirect()->route('petugas.transaksi.cetak.keluar', $transaksi->id_parkir)
                         ->with('success', 'Pembayaran berhasil & transaksi keluar selesai.');
    }

    // 3. Tampilan Cetak Tiket Masuk
    public function cetak($id)
    {
        $transaksi = Transaksi::with(['tarif', 'area', 'user'])->findOrFail($id);
        return view('petugas.transaksi.cetak', compact('transaksi'));
    }

    // 4. Tampilan Cetak Struk Pembayaran Keluar
    public function cetakKeluar($id)
    {
        $transaksi = Transaksi::with(['tarif', 'area', 'user'])->findOrFail($id);
        return view('petugas.transaksi.struk_keluar', compact('transaksi'));
    }
}