<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\Contracts\ProductStockInterface;
use Modules\Core\DTO\OrderItemDto;
use Modules\Core\Exceptions\InsufficientStockException;
use Modules\Core\Exceptions\ProductUnavailableException;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Exceptions\EmptyOrderLinesException;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

class CreateOrderAction
{
    public function __construct(
        private ProductCatalogInterface $catalog,
        private ProductStockInterface $stock,
    ) {}

    /**
     * @param  OrderItemDto[]  $lines
     */
    public function execute(
        string $customerName,
        string $customerEmail,
        ?string $customerPhone,
        ?string $shippingAddress,
        array $lines,
    ): Order {
        if ($lines === []) {
            throw new EmptyOrderLinesException;
        }

        return DB::transaction(function () use ($customerName, $customerEmail, $customerPhone, $shippingAddress, $lines): Order {
            $totalCents = 0;
            $preparedItems = [];

            foreach ($lines as $line) {
                if ($line->quantity < 1) {
                    continue;
                }

                $product = $this->catalog->findById($line->productId);

                if ($product === null) {
                    throw new ProductUnavailableException($line->productId);
                }

                if (! $this->stock->hasStock($line->productId, $line->quantity)) {
                    throw new InsufficientStockException($product->name);
                }

                $lineTotal = $product->priceCents * $line->quantity;
                $totalCents += $lineTotal;

                $preparedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price_cents' => $product->priceCents,
                    'quantity' => $line->quantity,
                ];
            }

            if ($preparedItems === []) {
                throw new EmptyOrderLinesException;
            }

            $order = Order::query()->create([
                'reference' => $this->generateReference(),
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'shipping_address' => $shippingAddress,
                'status' => OrderStatus::Pending,
                'total_cents' => $totalCents,
            ]);

            foreach ($preparedItems as $item) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    ...$item,
                ]);

                $this->stock->deductStock($item['product_id'], $item['quantity']);
            }

            return $order->load('items');
        });
    }

    private function generateReference(): string
    {
        do {
            $reference = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
