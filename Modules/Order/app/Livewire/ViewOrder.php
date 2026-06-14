<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Order\Actions\FindOrderByReferenceAction;
use Modules\Order\Models\Order;

#[Layout('order::layouts.storefront')]
class ViewOrder extends Component
{
    public string $reference;

    /**
     * @var array{
     *     reference: string,
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
     *     reference: string,
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
            'reference' => $order->reference,
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

    public function mount(string $reference, FindOrderByReferenceAction $finder): void
    {
        $this->reference = $reference;

        $order = $finder->execute($reference);

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
