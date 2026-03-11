<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_libro');
            $table->date('inicio_lectura')->nullable();
            $table->date('final_lectura')->nullable();
            $table->integer('tiempo_lectura')->nullable();
            $table->string('estado')->index();
            $table->string('puntuacion')->index()->nullable();
            $table->string('reseña')->nullable();

            $table->timestamps();

            $table->foreign('id_libro')
            ->references("id")
            ->on('libros')
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
        Schema::dropIfExists('lecturas');
    }
}
