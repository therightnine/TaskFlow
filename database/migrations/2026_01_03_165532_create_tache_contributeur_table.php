<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        

       Schema::create('tache_contributeur', function (Blueprint $table) {
    $table->engine = 'InnoDB';

    $table->id(); // bigint UNSIGNED is fine (not referenced)

    // MATCH taches.id and users.id EXACTLY
    $table->integer('id_tache');
    $table->integer('id_user');

    $table->foreign('id_tache')
        ->references('id')
        ->on('taches')
        ->onDelete('cascade');

    $table->foreign('id_user')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

    $table->unique(['id_tache', 'id_user']);
});


    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tache_contributeur');
    }
};
