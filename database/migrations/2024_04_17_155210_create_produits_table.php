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
        Schema::create('produits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('designation');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->integer('stock');
            $table->string('type');
            $table->string('couleur', 100)->nullable();
            $table->integer('reduction')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
