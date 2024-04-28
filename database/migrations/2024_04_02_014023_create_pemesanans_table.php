<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota');
            $table->string('gaun_kode');
            $table->foreign('gaun_kode')->references('kode')->on('gauns')->onDelete('cascade');
            $table->date('tanggal_sewa');
            $table->date('tanggal_ambil')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->string('nama_penyewa');
            $table->string('nomor_hp');
            $table->integer('harga');
            $table->integer('dp');
            $table->integer('sisa');
            $table->integer('deposit')->nullable();
            $table->string('note')->nullable();
            $table->date('tanggal_di_ambil')->nullable();
            $table->date('kembali')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('jenis_bank')->nullable();
            $table->string('atas_nama_2')->nullable();
            $table->date('tanggal_pengembalian_deposit')->nullable();
            $table->integer('deposit_pengambilan')->nullable();
            $table->integer('deposit_gaun')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('via_bayar')->nullable();
            $table->string('atas_nama')->nullable();
            $table->date('tanggal_pembayaran_2')->nullable();
            $table->string('via_bayar_2')->nullable();
            $table->string('atas_nama_22')->nullable();
            $table->string('nama_sales')->nullable();
            $table->enum('status', ['On-Going', 'Re-Schedule', 'Canceled', 'Finished'])->default('On-Going');
            // $table->string('deposit_nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
