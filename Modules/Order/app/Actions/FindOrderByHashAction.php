<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;

class FindOrderByHashAction
{
    public function execute(string $orderHash): ?Order
    {
        return Order::query()
            ->with('items')
            ->where('order_hash', $orderHash)
            ->first();
    }
}
