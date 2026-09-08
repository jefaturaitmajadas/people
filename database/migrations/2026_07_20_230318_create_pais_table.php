<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pais', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            // Relaciones usando los nombres de campo exactos que solicitaste
            $table->foreignId('id_empl_nacionalidad')->constrained('empl_nacionalidad');
            $table->foreignId('id_igss_pais')->constrained('igss_pais');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pais');
    }
};
