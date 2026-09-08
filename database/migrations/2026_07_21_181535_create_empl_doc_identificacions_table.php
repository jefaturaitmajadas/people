<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empl_doc_identificacion', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo'); // Número entero
            $table->string('documento_identificacion'); // Ej: DPI, Pasaporte
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empl_doc_identificacion');
    }
};
