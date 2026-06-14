<?php

namespace Modules\Order\Actions;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Exceptions\InvalidOrderStatusTransitionException;
use Modules\Order\Models\Order;

class UpdateOrderStatusAction
{
    public function execute(Order $order, OrderStatus $status): Order
    {
        if (! $order->status->canTransitionTo($status)) {
            throw new InvalidOrderStatusTransitionException($order->status, $status);
        }

        if ($order->status === $status) {
            return $order;
        }

        $order->update(['status' => $status]);

        return $order->fresh('items');
    }
}
