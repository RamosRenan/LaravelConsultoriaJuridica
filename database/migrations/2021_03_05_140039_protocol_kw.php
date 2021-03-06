<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProtocolKw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create
        Schema::create('protocol_kw', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('id_protocolo');
            $table->integer('id_keyword');
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
        // drop if exist table
        Schema::dropIfExists('protocol_kw');
    }
}
