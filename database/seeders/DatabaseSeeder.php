<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Catalog\Database\Seeders\CatalogDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@merchanto.test'],
            ['name' => 'Admin', 'password' => 'password'],
        );

        $this->call(CatalogDatabaseSeeder::class);
        $this->call(OrderDatabaseSeeder::class);
    }
}
