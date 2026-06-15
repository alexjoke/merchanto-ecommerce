<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\DTO\ProductDto;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use RuntimeException;

class OrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = app(ProductCatalogInterface::class);

        $orders = [
            [
                'order_hash' => 'ORD-DEMO0001',
                'customer_name' => 'Jane Doe',
                'customer_email' => 'jane@example.com',
                'customer_phone' => '+1 555 0101',
                'shipping_address' => '123 Main St, Springfield',
                'status' => OrderStatus::Pending,
                'lines' => [
                    ['product' => 'Wireless Headphones', 'quantity' => 1],
                ],
            ],
            [
                'order_hash' => 'ORD-DEMO0002',
                'customer_name' => 'John Smith',
                'customer_email' => 'john@example.com',
                'customer_phone' => null,
                'shipping_address' => '456 Oak Ave, Portland',
                'status' => OrderStatus::Confirmed,
                'lines' => [
                    ['product' => 'USB-C Hub', 'quantity' => 2],
                    ['product' => 'All-Weather Floor Mats', 'quantity' => 1],
                ],
            ],
            [
                'order_hash' => 'ORD-DEMO0003',
                'customer_name' => 'Alex Rivera',
                'customer_email' => 'alex@example.com',
                'customer_phone' => '+1 555 0199',
                'shipping_address' => null,
                'status' => OrderStatus::Delivered,
                'lines' => [
                    ['product' => 'All-Weather Floor Mats', 'quantity' => 2],
                ],
            ],
            [
                'order_hash' => 'ORD-DEMO0004',
                'customer_name' => 'Sam Taylor',
                'customer_email' => 'sam@example.com',
                'customer_phone' => null,
                'shipping_address' => '789 Pine Rd, Austin',
                'status' => OrderStatus::Cancelled,
                'lines' => [
                    ['product' => 'Wireless Headphones', 'quantity' => 1],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $this->seedOrder($catalog, $orderData);
        }
    }

    /**
     * @param  array{
     *     order_hash: string,
     *     customer_name: string,
     *     customer_email: string,
     *     customer_phone: string|null,
     *     shipping_address: string|null,
     *     status: OrderStatus,
     *     lines: array<int, array{product: string, quantity: int}>,
     * }  $orderData
     */
    private function seedOrder(ProductCatalogInterface $catalog, array $orderData): void
    {
        $preparedItems = [];
        $totalCents = 0;

        foreach ($orderData['lines'] as $line) {
            $product = $this->findProductByName($catalog, $line['product']);
            $preparedItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price_cents' => $product->priceCents,
                'quantity' => $line['quantity'],
            ];
            $totalCents += $product->priceCents * $line['quantity'];
        }

        $order = Order::query()->updateOrCreate(
            ['order_hash' => $orderData['order_hash']],
            [
                'customer_name' => $orderData['customer_name'],
                'customer_email' => $orderData['customer_email'],
                'customer_phone' => $orderData['customer_phone'],
                'shipping_address' => $orderData['shipping_address'],
                'status' => $orderData['status'],
                'total_cents' => $totalCents,
            ],
        );

        $order->items()->delete();

        foreach ($preparedItems as $item) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                ...$item,
            ]);
        }
    }

    private function findProductByName(ProductCatalogInterface $catalog, string $name): ProductDto
    {
        for ($id = 1; $id <= 100; $id++) {
            $product = $catalog->findById($id);

            if ($product?->name === $name) {
                return $product;
            }
        }

        throw new RuntimeException("Product [{$name}] not found. Run CatalogDatabaseSeeder first.");
    }
}
