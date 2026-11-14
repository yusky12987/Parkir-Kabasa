<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama_lengkap', 'username', 'password', 'role'];
    protected $hidden = ['password'];
}