<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    public function index() {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function create() {
        return view('admin.user.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|unique:tb_user,username',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas,owner'
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        LogAktivitas::catat(Auth::user()->id_user, "Menambah User: " . $request->username);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|unique:tb_user,username,'.$id.',id_user',
            'role' => 'required|in:admin,petugas,owner'
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        LogAktivitas::catat(Auth::user()->id_user, "Mengubah User: " . $user->username);
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        LogAktivitas::catat(Auth::user()->id_user, "Menghapus User: " . $user->username);
        $user->delete();
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus.');
    }
}