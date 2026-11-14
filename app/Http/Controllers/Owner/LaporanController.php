<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller {
    public function index(Request $request) {
        $query = Transaksi::with(['tarif', 'area', 'user'])->where('status', 'Keluar');

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('waktu_keluar', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }

        $laporan = $query->latest('id_parkir')->get();
        $totalPendapatan = $laporan->sum('biaya_total');

        return view('owner.laporan.index', compact('laporan', 'totalPendapatan'));
    }

    public function cetak(Request $request) {
        $query = Transaksi::with(['tarif', 'area', 'user'])->where('status', 'Keluar');

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('waktu_keluar', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }

        $laporan = $query->latest('id_parkir')->get();
        $totalPendapatan = $laporan->sum('biaya_total');

        return view('owner.laporan.cetak', compact('laporan', 'totalPendapatan'));
    }
}