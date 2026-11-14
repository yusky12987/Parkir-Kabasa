<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KendaraanController extends Controller {
    public function index() {
        $kendaraan = Kendaraan::all();
        return view('admin.kendaraan.index', compact('kendaraan'));
    }

    public function create() {
        return view('admin.kendaraan.create');
    }

    public function store(Request $request) {
        $request->validate([
            'no_plat' => 'required|string|max:15',
            'jenis_kendaraan' => 'required|string|max:20',
        ]);

        Kendaraan::create([
            'no_plat' => strtoupper($request->no_plat),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'pemilik' => $request->pemilik,
            'id_user' => Auth::user()->id_user
        ]);

        LogAktivitas::catat(Auth::user()->id_user, "Menambah Data Kendaraan: " . $request->no_plat);
        return redirect()->route('admin.kendaraan.index')->with('success', 'Data Kendaraan berhasil disimpan.');
    }

    public function edit($id) {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('admin.kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, $id) {
        $kendaraan = Kendaraan::findOrFail($id);
        $request->validate([
            'no_plat' => 'required|string|max:15',
            'jenis_kendaraan' => 'required|string|max:20',
        ]);

        $kendaraan->update([
            'no_plat' => strtoupper($request->no_plat),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'warna' => $request->warna,
            'pemilik' => $request->pemilik
        ]);

        LogAktivitas::catat(Auth::user()->id_user, "Mengubah Data Kendaraan: " . $kendaraan->no_plat);
        return redirect()->route('admin.kendaraan.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
    }

    public function destroy($id) {
        $kendaraan = Kendaraan::findOrFail($id);
        LogAktivitas::catat(Auth::user()->id_user, "Menghapus Data Kendaraan: " . $kendaraan->no_plat);
        $kendaraan->delete();
        return redirect()->route('admin.kendaraan.index')->with('success', 'Data Kendaraan berhasil dihapus.');
    }
}