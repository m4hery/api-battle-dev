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
        Schema::create('chocolat_commandes', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->integer('totalPrice');
            $table->string('chocolat_nom');
            $table->foreignId('commande_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chocolat_commandes');
    }
};
