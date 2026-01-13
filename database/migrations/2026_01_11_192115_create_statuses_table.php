<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();

            // clave estable para lógica (no depende del idioma)
            $table->string('key')->unique(); // ej: pending, paid, shipped...
            $table->string('name');          // ej: Pendiente, Pagado...
            $table->string('description')->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_final')->default(false); // delivered/cancelled/refunded...

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
