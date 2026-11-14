<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller {
    public function index() {
        $tarif = Tarif::all();
        return view('admin.tarif.index', compact('tarif'));
    }

    public function create() {
        return view('admin.tarif.create');
    }

    public function store(Request $request) {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:50',
            'tarif_per_jam' => 'required|numeric'
        ]);

        Tarif::create($request->all());
        LogAktivitas::catat(Auth::user()->id_user, "Menambah Tarif: " . $request->jenis_kendaraan);
        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit($id) {
        $tarif = Tarif::findOrFail($id);
        return view('admin.tarif.edit', compact('tarif'));
    }

    public function update(Request $request, $id) {
        $tarif = Tarif::findOrFail($id);
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:50',
            'tarif_per_jam' => 'required|numeric'
        ]);

        $tarif->update($request->all());
        LogAktivitas::catat(Auth::user()->id_user, "Mengubah Tarif: " . $tarif->jenis_kendaraan);
        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy($id) {
        $tarif = Tarif::findOrFail($id);
        LogAktivitas::catat(Auth::user()->id_user, "Menghapus Tarif: " . $tarif->jenis_kendaraan);
        $tarif->delete();
        return redirect()->route('admin.tarif.index')->with('success', 'Tarif berhasil dihapus.');
    }
}