<?php

namespace Modules\Core\Exceptions;

class InsufficientStockException extends CheckoutException
{
    public function __construct(string $productName)
    {
        parent::__construct("Insufficient stock for {$productName}.");
    }
}
