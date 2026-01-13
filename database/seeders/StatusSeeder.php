<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $statuses = [
            [
                'key' => 'pending',
                'name' => 'Pendiente',
                'description' => 'Pedido creado, pendiente de pago/confirmación.',
                'sort_order' => 10,
                'is_final' => false,
            ],
            [
                'key' => 'paid',
                'name' => 'Pagado',
                'description' => 'Pago recibido.',
                'sort_order' => 20,
                'is_final' => false,
            ],
            [
                'key' => 'processing',
                'name' => 'En preparación',
                'description' => 'Preparando el pedido.',
                'sort_order' => 30,
                'is_final' => false,
            ],
            [
                'key' => 'shipped',
                'name' => 'Enviado',
                'description' => 'Pedido enviado al cliente.',
                'sort_order' => 40,
                'is_final' => false,
            ],
            [
                'key' => 'delivered',
                'name' => 'Entregado',
                'description' => 'Pedido entregado.',
                'sort_order' => 50,
                'is_final' => true,
            ],
            [
                'key' => 'cancelled',
                'name' => 'Cancelado',
                'description' => 'Pedido cancelado.',
                'sort_order' => 90,
                'is_final' => true,
            ],
            [
                'key' => 'refunded',
                'name' => 'Reembolsado',
                'description' => 'Pago reembolsado.',
                'sort_order' => 95,
                'is_final' => true,
            ],
            [
                'key' => 'failed',
                'name' => 'Fallido',
                'description' => 'Error de pago u otro fallo.',
                'sort_order' => 99,
                'is_final' => true,
            ],
        ];

        foreach ($statuses as $s) {
            DB::table('statuses')->updateOrInsert(
                ['key' => $s['key']],
                array_merge($s, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
