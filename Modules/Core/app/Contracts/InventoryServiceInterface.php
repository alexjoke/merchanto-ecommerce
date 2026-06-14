<?php

namespace Modules\Core\Contracts;

interface InventoryServiceInterface
{
    public function isAvailable(int $productId, int $quantity): bool;

    public function decrementStock(int $productId, int $quantity): void;
}
