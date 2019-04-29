<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKemampuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kemampuan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_kompetensi')->unsigned();
            $table->string('nama_kemampuan');
            $table->timestamps();
            $table->foreign('id_kompetensi')
            ->references('id')->on('kompetensi')
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
        Schema::dropIfExists('kemampuan');
    }
}
