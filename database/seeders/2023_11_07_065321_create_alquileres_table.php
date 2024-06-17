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
        Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->date('ini_fecha')->nullable();
            $table->date('fin_fecha')->nullable();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('casilla_id')->nullable()->constrained('casillas');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias');
            $table->foreignId('precio_id')->nullable()->constrained('precios');
            $table->integer('estado')->default(1);
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
        Schema::dropIfExists('alquileres');
    }
};
