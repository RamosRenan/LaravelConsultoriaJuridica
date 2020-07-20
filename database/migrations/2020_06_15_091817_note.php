<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Note extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //tuples
        Schema::connection('legaladvice')->create('notes', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->foreign('registries_id')->references('id')->on('registries')->onDelete('cascade');
            $table->date('date_in');
            $table->string('inserted_by');
            $table->text('contain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //code ...
        Schema::dropIfExists('note');

    }
}
