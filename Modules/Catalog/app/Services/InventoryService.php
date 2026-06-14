<?php

namespace Modules\Catalog\Services;

use Modules\Catalog\Models\Product;
use Modules\Core\Contracts\InventoryServiceInterface;
use Modules\Core\Exceptions\InsufficientStockException;
use Modules\Core\Exceptions\InvalidQuantityException;
use Modules\Core\Exceptions\ProductUnavailableException;

class InventoryService implements InventoryServiceInterface
{
    public function isAvailable(int $productId, int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
        }

        $product = Product::query()->find($productId);

        if ($product === null || ! $product->is_published) {
            return false;
        }

        return $product->stock >= $quantity;
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidQuantityException;
        }

        $product = Product::query()->lockForUpdate()->find($productId);

        if ($product === null || ! $product->is_published) {
            throw new ProductUnavailableException($productId);
        }

        if ($product->stock < $quantity) {
            throw new InsufficientStockException($product->name);
        }

        $product->decrement('stock', $quantity);
    }
}
