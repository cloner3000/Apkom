<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuktiKompetensiWajib extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukti_kompetensi_wajib', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_mahasiswa')->unsigned();
            $table->string('nama_kompetensi_wajib');
            $table->string('bukti_wajib')->nullable();
            $table->boolean('active')->default(1);
            $table->string('pesan')->nullable($value = true);
            $table->timestamps();
            $table->foreign('id_mahasiswa')
            ->references('id')->on('mahasiswa')
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
        Schema::dropIfExists('bukti_kompetensi_wajib');
    }
}
