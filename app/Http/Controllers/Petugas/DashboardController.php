<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\AreaParkir;
use App\Models\Kendaraan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $parkirMasuk = Transaksi::where('status', 'Masuk')->count();
        $transaksiHariIni = Transaksi::whereDate('waktu_masuk', today())->count();
        $area = AreaParkir::all();

        // Ambil data Member untuk menampilkan nama pemilik kendaraan
        $members = Kendaraan::all()->keyBy(function ($item) {
            return strtoupper(trim($item->no_plat));
        });

        // Ambil daftar kendaraan yang sedang parkir ('Masuk') dan kelompokkan per id_area
        $parkirAktif = Transaksi::with('tarif')
            ->where('status', 'Masuk')
            ->orderBy('waktu_masuk', 'desc')
            ->get()
            ->groupBy('id_area')
            ->map(function ($items) use ($members) {
                return $items->map(function ($t) use ($members) {
                    $plat = strtoupper(trim($t->no_plat));
                    $member = $members->get($plat);

                    return [
                        'id_parkir'   => $t->id_parkir,
                        'no_plat'     => $plat,
                        'pemilik'     => $member ? ($member->pemilik ?? 'Member') : null,
                        'is_member'   => (bool) $member,
                        'jenis'       => $t->tarif->jenis_kendaraan ?? '-',
                        'waktu_masuk' => Carbon::parse($t->waktu_masuk)->format('d/m/Y H:i'),
                        'url_keluar'  => route('petugas.transaksi.keluar', ['no_plat' => $plat]),
                    ];
                })->values();
            });

        return view('petugas.dashboard', compact(
            'parkirMasuk',
            'transaksiHariIni',
            'area',
            'parkirAktif'
        ));
    }
}