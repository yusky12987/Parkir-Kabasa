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

class TransaksiController extends Controller {

    // 1. Tampilan Input Kendaraan Masuk
    public function masuk() {
        $tarif = Tarif::all();
        $area = AreaParkir::all();
        $kendaraan = Kendaraan::orderBy('no_plat', 'asc')->get();

        return view('petugas.transaksi.masuk', compact('tarif', 'area', 'kendaraan'));
    }

    // Process Simpan Kendaraan Masuk
    public function storeMasuk(Request $request) {
        $request->validate([
            'no_plat'  => 'required|string|max:15',
            'id_tarif' => 'required|exists:tb_tarif,id_tarif',
            'id_area'  => 'required|exists:tb_area_parkir,id_area',
            'warna'    => 'nullable|string|max:30',
            'pemilik'  => 'nullable|string|max:100',
        ]);

        $area = AreaParkir::findOrFail($request->id_area);
        if ($area->terisi >= $area->kapasitas) {
            return back()->withErrors(['id_area' => 'Area parkir ini sudah penuh!']);
        }

        $noPlat = strtoupper(trim($request->no_plat));
        $tarifDipilih = Tarif::findOrFail($request->id_tarif);

        // Cek apakah plat sudah terdaftar di tb_kendaraan (dari Admin).
        // Jika belum ada, otomatis simpan ke tb_kendaraan sebagai data baru.
        Kendaraan::firstOrCreate(
            ['no_plat' => $noPlat],
            [
                'jenis_kendaraan' => $tarifDipilih->jenis_kendaraan,
                'warna'           => $request->filled('warna') ? trim($request->warna) : '-',
                'pemilik'         => $request->filled('pemilik') ? trim($request->pemilik) : 'Umum',
                'id_user'         => Auth::user()->id_user,
            ]
        );

        $transaksi = Transaksi::create([
            'no_plat'     => $noPlat,
            'waktu_masuk' => Carbon::now(),
            'id_tarif'    => $request->id_tarif,
            'id_area'     => $request->id_area,
            'id_user'     => Auth::user()->id_user,
            'status'      => 'Masuk'
        ]);

        // Tambah jumlah terisi di area parkir
        $area->increment('terisi');

        // Catat Log
        LogAktivitas::catat(Auth::user()->id_user, "Kendaraan Masuk: " . $transaksi->no_plat);

        return redirect()->route('petugas.transaksi.cetak', $transaksi->id_parkir)
                         ->with('success', 'Transaksi masuk berhasil dicatat.');
    }

    // 2. Tampilan Transaksi Keluar & Bayar
    public function keluar(Request $request) {
        $transaksi = null;
        $daftarParkir = Transaksi::with(['tarif', 'area'])
            ->where('status', 'Masuk')
            ->latest('id_parkir')
            ->get();

        if ($request->has('no_plat') && $request->no_plat != '') {
            $transaksi = Transaksi::with(['tarif', 'area'])
                ->where('no_plat', strtoupper(trim($request->no_plat)))
                ->where('status', 'Masuk')
                ->latest('id_parkir')
                ->first();

            if ($transaksi) {
                $masuk = Carbon::parse($transaksi->waktu_masuk);
                $keluar = Carbon::now();
                // Hitung durasi jam (minimal 1 jam, pembulatan ke atas)
                $durasi = max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));
                $biaya = $durasi * $transaksi->tarif->tarif_per_jam;

                $transaksi->waktu_keluar_temp = $keluar->format('Y-m-d H:i:s');
                $transaksi->durasi_temp = $durasi;
                $transaksi->biaya_temp = $biaya;
            }
        }

        return view('petugas.transaksi.keluar', compact('transaksi', 'daftarParkir'));
    }

    // Process Pembayaran Keluar
    public function storeKeluar(Request $request, $id) {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->status == 'Keluar') {
            return redirect()->route('petugas.transaksi.keluar')->withErrors(['err' => 'Transaksi sudah selesai.']);
        }

        $masuk = Carbon::parse($transaksi->waktu_masuk);
        $keluar = Carbon::now();
        $durasi = max(1, (int) ceil($masuk->diffInMinutes($keluar) / 60));
        $biaya = $durasi * $transaksi->tarif->tarif_per_jam;

        $transaksi->update([
            'waktu_keluar' => $keluar,
            'durasi_jam'   => $durasi,
            'biaya_total'  => $biaya,
            'status'       => 'Keluar'
        ]);

        // Kurangi jumlah terisi
        AreaParkir::where('id_area', $transaksi->id_area)->decrement('terisi');

        // Catat Log
        LogAktivitas::catat(Auth::user()->id_user, "Kendaraan Keluar: " . $transaksi->no_plat);

        return redirect()->route('petugas.transaksi.cetak', $transaksi->id_parkir)
                         ->with('success', 'Transaksi keluar selesai.');
    }

    // 3. Tampilan Cetak Struk
    public function cetak($id) {
        $transaksi = Transaksi::with(['tarif', 'area', 'user'])->findOrFail($id);
        return view('petugas.transaksi.cetak', compact('transaksi'));
    }
}