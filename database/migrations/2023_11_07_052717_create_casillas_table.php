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
        Schema::create('casillas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('observacion')->nullable();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias');
            $table->foreignId('seccione_id')->nullable()->constrained('secciones');
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
        Schema::dropIfExists('casillas');
    }
};
