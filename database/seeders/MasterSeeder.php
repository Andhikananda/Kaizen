<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Gunakan ini
use App\Models\PriceType;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Mengisi Role Utama
        $roles = ['admin', 'kasir', 'mekanik'];
        foreach ($roles as $role) {
            // Kita tambah guard_name 'api' karena ini project backend API
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'api'
            ]);
        }

        // 2. Mengisi Price Types
        $priceTypes = [
            ['name' => 'Normal', 'code' => 'normal'],
            ['name' => 'Bengkel', 'code' => 'workshop'],
            ['name' => 'Ojol', 'code' => 'taxi'],
            ['name' => 'Rental', 'code' => 'rental'],
        ];

        foreach ($priceTypes as $type) {
            PriceType::firstOrCreate(
                ['code' => $type['code']],
                ['name' => $type['name']]
            );
        }
    }
}
