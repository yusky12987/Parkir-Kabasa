<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class DashboardController extends Controller {
    public function index() {
        $totalPendapatan = Transaksi::where('status', 'Keluar')->sum('biaya_total');
        $totalTransaksi = Transaksi::where('status', 'Keluar')->count();
        $pendapatanHariIni = Transaksi::where('status', 'Keluar')->whereDate('waktu_keluar', today())->sum('biaya_total');

        return view('owner.dashboard', compact('totalPendapatan', 'totalTransaksi', 'pendapatanHariIni'));
    }
}