<?php

namespace Modules\Core\Contracts;

interface ProductStockInterface
{
    public function hasStock(int $productId, int $quantity): bool;

    public function deductStock(int $productId, int $quantity): void;
}
