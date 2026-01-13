<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('label')->nullable(); // Casa, Trabajo
            $table->string('recipient_name');
            $table->string('phone')->nullable();

            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('postcode', 20);
            $table->string('country', 2)->default('ES');

            $table->unsignedTinyInteger('is_default')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
