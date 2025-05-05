<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('accion')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('casilla')->nullable();
            $table->dateTime('fecha_hora')->nullable();
            $table->foreignId('cajero_id')->nullable()->constrained('cajeros');
            $table->foreignId('casilla_id')->nullable()->constrained('casillas');

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
        Schema::dropIfExists('eventos');
    }
};
