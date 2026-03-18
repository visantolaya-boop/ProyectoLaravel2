<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->index();
            $table->string('autor')->index();
            $table->smallInteger('anio')->index();
            $table->string('editorial')->index();
            $table->integer('paginas');
            $table->foreignId('genero_id')->nullable()->deafault(1)->constrained('generos')->onDelete('set null');
            $table->string('portada')->nullable();
            $table->foreignId('ubicacion_id')->nullable()->deafault(1)->constrained('ubicacion')->onDelete('set null');


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
        Schema::table('libros', function (Blueprint $table) {
        $table->dropForeign(['ubicacion_id']);
        $table->dropColumn('ubicacion_id');
        $table->dropForeign(['genero_id']);
        $table->dropColumn('genero_id');
        });
        
        Schema::dropIfExists('libros');
    }
}
