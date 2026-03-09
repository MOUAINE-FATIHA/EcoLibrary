<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('cascade');
            $table->string('titre');
            $table->string('auteur');
            $table->string('isbn')->unique()->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('total_exemplaires')->default(1);
            $table->unsignedInteger('exemplaires_dispo')->default(1);
            $table->unsignedInteger('exemplaires_degrades')->default(0);
            $table->unsignedBigInteger('nb_consultations')->default(0);
            $table->date('date_publication')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};