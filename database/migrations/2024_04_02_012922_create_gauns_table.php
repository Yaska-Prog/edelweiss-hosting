<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gauns', function (Blueprint $table) {
            $table->string('kode')->primary();
            $table->text('gambar');
            $table->string('warna');
            $table->enum('usia', ['Dewasa', 'Anak'])->default('Dewasa');
            $table->integer('harga');
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
        Schema::dropIfExists('gauns');
    }
}
