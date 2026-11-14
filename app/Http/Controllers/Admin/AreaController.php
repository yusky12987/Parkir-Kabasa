<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AreaParkir;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller {
    public function index() {
        $area = AreaParkir::all();
        return view('admin.area.index', compact('area'));
    }

    public function create() {
        return view('admin.area.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer'
        ]);

        AreaParkir::create([
            'nama_area' => $request->nama_area,
            'kapasitas' => $request->kapasitas,
            'terisi' => 0
        ]);

        LogAktivitas::catat(Auth::user()->id_user, "Menambah Area: " . $request->nama_area);
        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil ditambahkan.');
    }

    public function edit($id) {
        $area = AreaParkir::findOrFail($id);
        return view('admin.area.edit', compact('area'));
    }

    public function update(Request $request, $id) {
        $area = AreaParkir::findOrFail($id);
        $request->validate([
            'nama_area' => 'required|string|max:50',
            'kapasitas' => 'required|integer'
        ]);

        $area->update($request->only(['nama_area', 'kapasitas']));
        LogAktivitas::catat(Auth::user()->id_user, "Mengubah Area: " . $area->nama_area);
        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil diperbarui.');
    }

    public function destroy($id) {
        $area = AreaParkir::findOrFail($id);
        LogAktivitas::catat(Auth::user()->id_user, "Menghapus Area: " . $area->nama_area);
        $area->delete();
        return redirect()->route('admin.area.index')->with('success', 'Area parkir berhasil dihapus.');
    }
}