<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skpi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_mahasiswa')->unsigned();
            $table->string('status', 10)->default('progress');
            $table->float('point_skpi')->default(0);
            $table->string('qr_code');
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
        Schema::dropIfExists('skpi');
    }
}
