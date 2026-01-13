<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Añadimos slug nullable primero (para poder poblar sin problemas)
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // 2) Poblar slug para productos existentes (y garantizar unicidad)
        DB::table('products')
            ->select('id', 'name')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                foreach ($products as $p) {
                    $base = Str::slug($p->name);

                    // fallback por si el nombre queda vacío al “slugificar”
                    if ($base === '') {
                        $base = 'producto';
                    }

                    $slug = $base;
                    $i = 2;

                    while (
                        DB::table('products')
                        ->where('slug', $slug)
                        ->where('id', '!=', $p->id)
                        ->exists()
                    ) {
                        $slug = $base . '-' . $i;
                        $i++;
                    }

                    DB::table('products')
                        ->where('id', $p->id)
                        ->update(['slug' => $slug]);
                }
            });

        // 3) Hacer slug obligatorio + único
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
