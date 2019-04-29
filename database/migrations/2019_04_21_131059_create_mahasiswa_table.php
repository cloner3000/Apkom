<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMahasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_jurusan')->unsigned();
            $table->bigInteger('id_account')->unsigned();
            $table->bigInteger('npm')->unique()->unsigned();
            $table->string('nama');
            $table->string('kota_lahir');
            $table->date('tgl_lahir');
            $table->string('no_ijazah')->unique();
            $table->date('tgl_masuk');
            $table->date('tgl_lulus');
            $table->float('ipk');
            $table->float('total_point')->default(0);
            $table->timestamps();
            $table->foreign('id_jurusan')
            ->references('id')->on('jurusan')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('id_account')
            ->references('id')->on('users')
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
        Schema::dropIfExists('mahasiswa');
    }
}
