<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remisearticles', function (Blueprint $table) {
            $table->id();
            $table->integer('nbr_article');
            $table->integer('remise');
            $table->double('prix_min');
            $table->double('prix_max');
            $table->string('signe_min');
            $table->string('signe_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remisearticles');
    }
};
