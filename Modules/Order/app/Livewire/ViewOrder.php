<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Order\Actions\FindOrderByHashAction;
use Modules\Order\Models\Order;

#[Layout('order::layouts.storefront')]
class ViewOrder extends Component
{
    public string $orderHash;

    /**
     * @var array{
     *     orderHash: string,
     *     customerName: string,
     *     customerEmail: string,
     *     customerPhone: string|null,
     *     shippingAddress: string|null,
     *     status: string,
     *     statusLabel: string,
     *     totalCents: int,
     *     items: array<int, array{
     *         productName: string,
     *         quantity: int,
     *         unitPriceCents: int,
     *         subtotalCents: int,
     *     }>,
     *     createdAt: string,
     * }|null
     */
    public ?array $order = null;

    /**
     * @return array{
     *     orderHash: string,
     *     customerName: string,
     *     customerEmail: string,
     *     customerPhone: string|null,
     *     shippingAddress: string|null,
     *     status: string,
     *     statusLabel: string,
     *     totalCents: int,
     *     items: array<int, array{
     *         productName: string,
     *         quantity: int,
     *         unitPriceCents: int,
     *         subtotalCents: int,
     *     }>,
     *     createdAt: string,
     * }
     */
    public static function orderToArray(Order $order): array
    {
        return [
            'orderHash' => $order->order_hash,
            'customerName' => $order->customer_name,
            'customerEmail' => $order->customer_email,
            'customerPhone' => $order->customer_phone,
            'shippingAddress' => $order->shipping_address,
            'status' => $order->status->value,
            'statusLabel' => $order->status->label(),
            'totalCents' => $order->total_cents,
            'items' => $order->items->map(fn ($item): array => [
                'productName' => $item->product_name,
                'quantity' => $item->quantity,
                'unitPriceCents' => $item->unit_price_cents,
                'subtotalCents' => $item->subtotalCents(),
            ])->all(),
            'createdAt' => $order->created_at?->toDateTimeString() ?? '',
        ];
    }

    public function mount(string $orderHash, FindOrderByHashAction $finder): void
    {
        $this->orderHash = $orderHash;

        $order = $finder->execute($orderHash);

        if ($order === null) {
            abort(404);
        }

        $this->order = self::orderToArray($order);
    }

    public function render(): View
    {
        return view('order::livewire.view-order');
    }
}
