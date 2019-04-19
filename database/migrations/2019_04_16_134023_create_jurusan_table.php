<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurusanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('id_account')->unsigned()->unique();
            $table->string('nama_jurusan');
            $table->string('program');
            $table->string('jenis_pendidikan');
            $table->string('fakultas');
            $table->string('gelar');
            $table->timestamps();
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
        Schema::dropIfExists('jurusan');
    }
}
