<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model {
    protected $table = 'tb_kendaraan';
    protected $primaryKey = 'id_kendaraan';
    protected $fillable = ['no_plat', 'jenis_kendaraan', 'warna', 'pemilik', 'id_user'];
}