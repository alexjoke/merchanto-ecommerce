<?php

namespace Modules\Order\Exceptions;

use Modules\Core\Exceptions\CheckoutException;

class EmptyOrderLinesException extends CheckoutException
{
    public function __construct()
    {
        parent::__construct('Please select at least one product.');
    }
}
