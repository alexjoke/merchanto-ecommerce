<?php

namespace Modules\Order\Exceptions;

use Modules\Order\Enums\OrderStatus;

class InvalidOrderStatusTransitionException extends OrderException
{
    public function __construct(OrderStatus $from, OrderStatus $to)
    {
        parent::__construct(
            "Cannot change order status from {$from->label()} to {$to->label()}."
        );
    }
}
