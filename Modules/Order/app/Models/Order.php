<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Order\Enums\OrderStatus;

/**
 * @property int $id
 * @property string $reference
 * @property string $customer_name
 * @property string $customer_email
 * @property string|null $customer_phone
 * @property string|null $shipping_address
 * @property OrderStatus $status
 * @property int $total_cents
 * @property Carbon|null $created_at
 * @property-read Collection<int, OrderItem> $items
 */
#[Fillable([
    'reference',
    'customer_name',
    'customer_email',
    'customer_phone',
    'shipping_address',
    'status',
    'total_cents',
])]
class Order extends Model
{
    protected $table = 'order_orders';

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_cents' => 'integer',
        ];
    }
}
