<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 1. Tabel User
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_lengkap', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'petugas', 'owner']);
            $table->timestamps();
        });

        // 2. Tabel Tarif
        Schema::create('tb_tarif', function (Blueprint $table) {
            $table->id('id_tarif');
            $table->string('jenis_kendaraan', 50);
            $table->decimal('tarif_per_jam', 10, 0);
            $table->timestamps();
        });

        // 3. Tabel Area Parkir
        Schema::create('tb_area_parkir', function (Blueprint $table) {
            $table->id('id_area');
            $table->string('nama_area', 50);
            $table->integer('kapasitas');
            $table->integer('terisi')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Kendaraan
        Schema::create('tb_kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->string('no_plat', 15);
            $table->string('jenis_kendaraan', 20);
            $table->string('warna', 20)->nullable();
            $table->string('pemilik', 100)->nullable();
            $table->foreignId('id_user')->nullable()->constrained('tb_user', 'id_user')->nullOnDelete();
            $table->timestamps();
        });

        // 5. Tabel Transaksi Parkir
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_parkir');
            $table->string('no_plat', 15);
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->foreignId('id_tarif')->constrained('tb_tarif', 'id_tarif');
            $table->integer('durasi_jam')->nullable();
            $table->decimal('biaya_total', 10, 0)->nullable();
            $table->enum('status', ['Masuk', 'Keluar'])->default('Masuk');
            $table->foreignId('id_user')->constrained('tb_user', 'id_user');
            $table->foreignId('id_area')->constrained('tb_area_parkir', 'id_area');
            $table->timestamps();
        });

        // 6. Tabel Log Aktivitas
        Schema::create('tb_log_aktivitas', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_user')->constrained('tb_user', 'id_user')->onDelete('cascade');
            $table->string('aktivitas', 100);
            $table->timestamp('waktu_aktivitas')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tb_log_aktivitas');
        Schema::dropIfExists('tb_transaksi');
        Schema::dropIfExists('tb_kendaraan');
        Schema::dropIfExists('tb_area_parkir');
        Schema::dropIfExists('tb_tarif');
        Schema::dropIfExists('tb_user');
    }
};