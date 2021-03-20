<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkripsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skripsis', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('judulskripsi');
            $table->timestamps();
            $table->primary('nim');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('skripsis');
    }
}
