<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;

class FindOrderByReferenceAction
{
    public function execute(string $reference): ?Order
    {
        return Order::query()
            ->with('items')
            ->where('reference', $reference)
            ->first();
    }
}
