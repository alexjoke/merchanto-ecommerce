<?php

namespace Modules\Core\Exceptions;

class ProductUnavailableException extends CheckoutException
{
    public function __construct(int $productId)
    {
        parent::__construct("Product #{$productId} is not available.");
    }
}
