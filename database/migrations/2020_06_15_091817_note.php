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
            $table->integer('registries_id');
            $table->date('date_in');
            $table->string('inserted_by');
            $table->text('contain');
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
        //code ...
        Schema::dropIfExists('notes');

    }
}
