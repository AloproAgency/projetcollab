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
        // Ajout de la colonne 'user_id' à la table 'projects'
        Schema::table('projects', function (Blueprint $table) {
            // Ajout de la colonne 'user_id' comme clé étrangère
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression de la colonne 'user_id' si la migration est annulée
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
