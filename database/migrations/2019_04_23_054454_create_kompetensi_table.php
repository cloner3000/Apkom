<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKompetensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompetensi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_mahasiswa')->unsigned();
            $table->bigInteger('id_bidang')->unsigned();
            $table->string('nama_kompetensi');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('tingkat');
            $table->string('peran');
            $table->double('point_kompetensi');
            $table->string('bukti');
            $table->timestamps();
            $table->foreign('id_mahasiswa')
            ->references('id')->on('mahasiswa')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('id_bidang')
            ->references('id')->on('bidang_kompetensi')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kompetensi');
    }
}
