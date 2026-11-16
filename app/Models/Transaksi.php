<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model {
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_parkir';
    protected $fillable = [
        'no_plat', 'waktu_masuk', 'waktu_keluar', 'id_tarif',
        'durasi_jam', 'biaya_total', 'metode_pembayaran',
        'uang_bayar', 'kembalian', 'status', 'id_user', 'id_area'
    ];
    public function tarif() {
        return $this->belongsTo(Tarif::class, 'id_tarif');
    }
    public function area() {
        return $this->belongsTo(AreaParkir::class, 'id_area');
    }
    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}