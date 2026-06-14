<?php

namespace Modules\Catalog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

class CatalogDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::query()->updateOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics'],
        );

        $cars = Category::query()->updateOrCreate(
            ['slug' => 'cars'],
            ['name' => 'Cars'],
        );

        $products = [
            [
                'category_id' => $electronics->id,
                'name' => 'Wireless Headphones',
                'slug' => 'wireless-headphones',
                'description' => 'Noise-cancelling over-ear headphones with 30-hour battery life.',
                'price_cents' => 14999,
                'stock' => 25,
                'is_published' => true,
            ],
            [
                'category_id' => $electronics->id,
                'name' => 'USB-C Hub',
                'slug' => 'usb-c-hub',
                'description' => '7-in-1 adapter with HDMI, USB 3.0, and SD card reader.',
                'price_cents' => 4999,
                'stock' => 40,
                'is_published' => true,
            ],
            [
                'category_id' => $cars->id,
                'name' => 'All-Weather Floor Mats',
                'slug' => 'all-weather-floor-mats',
                'description' => 'Custom-fit rubber floor mats for year-round protection.',
                'price_cents' => 8999,
                'stock' => 50,
                'is_published' => true,
            ],
            [
                'category_id' => $cars->id,
                'name' => 'Magnetic Phone Mount',
                'slug' => 'magnetic-phone-mount',
                'description' => 'Vent-mounted holder with strong magnetic grip for smartphones.',
                'price_cents' => 2499,
                'stock' => 0,
                'is_published' => true,
            ],
            [
                'category_id' => null,
                'name' => 'Draft Product',
                'slug' => 'draft-product',
                'description' => 'Unpublished product for admin testing.',
                'price_cents' => 999,
                'stock' => 10,
                'is_published' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product,
            );
        }
    }
}
