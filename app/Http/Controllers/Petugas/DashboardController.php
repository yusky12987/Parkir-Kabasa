<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\AreaParkir;

class DashboardController extends Controller {
    public function index() {
        $parkirMasuk = Transaksi::where('status', 'Masuk')->count();
        $transaksiHariIni = Transaksi::whereDate('waktu_masuk', today())->count();
        $area = AreaParkir::all();

        return view('petugas.dashboard', compact('parkirMasuk', 'transaksiHariIni', 'area'));
    }
}