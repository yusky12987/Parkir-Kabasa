<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['Tunai', 'QRIS'])->nullable()->after('biaya_total');
            $table->decimal('uang_bayar', 10, 0)->nullable()->after('metode_pembayaran');
            $table->decimal('kembalian', 10, 0)->nullable()->after('uang_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'uang_bayar', 'kembalian']);
        });
    }
};