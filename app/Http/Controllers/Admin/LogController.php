<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;

class LogController extends Controller {
    public function index() {
        $logs = LogAktivitas::with('user')->latest('id_log')->paginate(20);
        return view('admin.log.index', compact('logs'));
    }
}