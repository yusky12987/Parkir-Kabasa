<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Trigger otomatis saat kendaraan MASUK (Insert ke tb_transaksi)
        DB::unprepared("
            CREATE TRIGGER trg_kendaraan_masuk
            AFTER INSERT ON tb_transaksi
            FOR EACH ROW
            BEGIN
                IF NEW.status = 'Masuk' THEN
                    UPDATE tb_area_parkir 
                    SET terisi = terisi + 1 
                    WHERE id_area = NEW.id_area;
                END IF;
            END
        ");

        // 2. Trigger otomatis saat kendaraan KELUAR (Update status di tb_transaksi)
        DB::unprepared("
            CREATE TRIGGER trg_kendaraan_keluar
            AFTER UPDATE ON tb_transaksi
            FOR EACH ROW
            BEGIN
                IF OLD.status = 'Masuk' AND NEW.status = 'Keluar' THEN
                    UPDATE tb_area_parkir 
                    SET terisi = terisi - 1 
                    WHERE id_area = NEW.id_area;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_kendaraan_masuk');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_kendaraan_keluar');
    }
};