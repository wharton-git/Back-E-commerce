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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Assurez-vous d'avoir la table 'users' avec la colonne 'id'
            $table->dateTime('date_commande');
            $table->decimal('prix_commande', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
