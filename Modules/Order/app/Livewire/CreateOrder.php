<?php

namespace Modules\Order\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\DTO\OrderItemDto;
use Modules\Core\DTO\ProductDto;
use Modules\Core\Exceptions\CheckoutException;
use Modules\Order\Actions\CreateOrderAction;

#[Layout('order::layouts.storefront')]
class CreateOrder extends Component
{
    /**
     * @var array<int, array{
     *     id: int,
     *     name: string,
     *     description: string,
     *     priceCents: int,
     *     stock: int,
     *     categoryId: int|null,
     *     categoryName: string|null,
     * }>
     */
    public array $products = [];

    /** @var array<int, int> */
    public array $quantities = [];

    public string $customerName = '';

    public string $customerEmail = '';

    public string $customerPhone = '';

    public string $shippingAddress = '';

    public function mount(ProductCatalogInterface $catalog): void
    {
        $this->products = $catalog->listAvailable()
            ->map(fn (ProductDto $product): array => $product->toArray())
            ->values()
            ->all();

        foreach ($this->products as $product) {
            $this->quantities[$product['id']] = 0;
        }
    }

    public function submit(CreateOrderAction $createOrder): void
    {
        $validated = $this->validate([
            'customerName' => ['required', 'string', 'max:255'],
            'customerEmail' => ['required', 'email', 'max:255'],
            'customerPhone' => ['nullable', 'string', 'max:50'],
            'shippingAddress' => ['nullable', 'string', 'max:1000'],
            'quantities' => ['array'],
            'quantities.*' => ['integer', 'min:0'],
        ]);

        $lines = collect($this->quantities)
            ->filter(fn (int $quantity): bool => $quantity > 0)
            ->map(fn (int $quantity, int|string $productId): OrderItemDto => new OrderItemDto(
                productId: (int) $productId,
                quantity: $quantity,
            ))
            ->values()
            ->all();

        try {
            $order = $createOrder->execute(
                customerName: $validated['customerName'],
                customerEmail: $validated['customerEmail'],
                customerPhone: $validated['customerPhone'] !== '' ? $validated['customerPhone'] : null,
                shippingAddress: $validated['shippingAddress'] !== '' ? $validated['shippingAddress'] : null,
                lines: $lines,
            );
        } catch (CheckoutException $exception) {
            throw ValidationException::withMessages([
                'quantities' => $exception->getMessage(),
            ]);
        }

        $this->redirectRoute('order.view', ['orderHash' => $order->order_hash], navigate: true);
    }

    public function render(): View
    {
        return view('order::livewire.create-order');
    }
}
