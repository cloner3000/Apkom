<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKompetensiWajibTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompetensi_wajib', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_jurusan')->unsigned();
            $table->string('nama_kompetensi_wajib');
            $table->timestamps();
            $table->foreign('id_jurusan')
            ->references('id')->on('jurusan')
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
        Schema::dropIfExists('kompetensi_wajib');
    }
}
