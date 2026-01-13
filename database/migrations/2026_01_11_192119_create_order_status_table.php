<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('statuses')->restrictOnDelete();

            // opcional: quién hizo el cambio (admin)
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();

            // opcional: nota interna
            $table->string('comment')->nullable();

            // cuándo se asignó ese estado
            $table->timestamp('created_at')->useCurrent();

            // índice útil para timeline y consultas
            $table->index(['order_id', 'created_at']);
            $table->index(['order_id', 'status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status');
    }
};
