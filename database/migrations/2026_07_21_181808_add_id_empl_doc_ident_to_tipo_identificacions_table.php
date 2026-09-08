<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipo_identificacions', function (Blueprint $table) {
            // Agregamos el campo y su relación
            $table->foreignId('id_empl_doc_ident')->nullable()->constrained('empl_doc_identificacion')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tipo_identificacions', function (Blueprint $table) {
            $table->dropForeign(['id_empl_doc_ident']);
            $table->dropColumn('id_empl_doc_ident');
        });
    }
};
