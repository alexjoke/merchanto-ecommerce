<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $product_name
 * @property int $unit_price_cents
 * @property int $quantity
 * @property-read Order $order
 */
#[Fillable([
    'order_id',
    'product_id',
    'product_name',
    'unit_price_cents',
    'quantity',
])]
class OrderItem extends Model
{
    protected $table = 'order_items';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function subtotalCents(): int
    {
        return $this->unit_price_cents * $this->quantity;
    }

    protected function casts(): array
    {
        return [
            'product_id' => 'integer',
            'unit_price_cents' => 'integer',
            'quantity' => 'integer',
        ];
    }
}
