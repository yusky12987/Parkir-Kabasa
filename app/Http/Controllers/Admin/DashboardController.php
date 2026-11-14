<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tarif;
use App\Models\AreaParkir;
use App\Models\Transaksi;

class DashboardController extends Controller {
    public function index() {
        $totalUser = User::count();
        $totalTarif = Tarif::count();
        $totalArea = AreaParkir::count();
        $transaksiHariIni = Transaksi::whereDate('waktu_masuk', today())->count();

        return view('admin.dashboard', compact('totalUser', 'totalTarif', 'totalArea', 'transaksiHariIni'));
    }
}