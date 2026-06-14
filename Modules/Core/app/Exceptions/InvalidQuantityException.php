<?php

namespace Modules\Core\Exceptions;

class InvalidQuantityException extends CheckoutException
{
    public function __construct()
    {
        parent::__construct('Quantity must be at least 1.');
    }
}
