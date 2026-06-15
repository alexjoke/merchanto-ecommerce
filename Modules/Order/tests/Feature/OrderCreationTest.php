<?php

namespace Modules\Order\Tests\Feature;

use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\Contracts\ProductStockInterface;
use Modules\Core\DTO\OrderItemDto;
use Modules\Order\Actions\CreateOrderAction;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Livewire\CreateOrder;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

beforeEach(function (): void {
    seedCatalog();
});

it('creates an order through the checkout livewire component', function (): void {
    $product = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();

    Livewire::test(CreateOrder::class)
        ->set('quantities.' . $product->id, 1)
        ->set('customerName', 'Jane Doe')
        ->set('customerEmail', 'jane@example.com')
        ->set('customerPhone', '+1 555 0100')
        ->set('shippingAddress', '123 Test Street')
        ->call('submit')
        ->assertHasNoErrors();

    $order = Order::query()->first();

    expect($order)->not->toBeNull()
        ->and($order->customer_name)->toBe('Jane Doe')
        ->and($order->customer_email)->toBe('jane@example.com')
        ->and($order->status)->toBe(OrderStatus::Pending)
        ->and($order->order_hash)->toStartWith('ORD-');

    assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => 'Wireless Headphones',
        'unit_price_cents' => 14999,
        'quantity' => 1,
    ]);

    expect($order->total_cents)->toBe(14999);
});

it('redirects to the order view page after checkout', function (): void {
    $product = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();

    Livewire::test(CreateOrder::class)
        ->set('quantities.' . $product->id, 1)
        ->set('customerName', 'Redirect Tester')
        ->set('customerEmail', 'redirect@example.com')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('order.view', ['orderHash' => Order::query()->firstOrFail()->order_hash]));
});

it('creates an order with multiple products and calculates total_cents', function (): void {
    $headphones = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();
    $hub = Product::query()->where('slug', 'usb-c-hub')->firstOrFail();

    Livewire::test(CreateOrder::class)
        ->set('quantities.' . $headphones->id, 2)
        ->set('quantities.' . $hub->id, 1)
        ->set('customerName', 'Multi Item')
        ->set('customerEmail', 'multi@example.com')
        ->call('submit')
        ->assertHasNoErrors();

    $order = Order::query()->with('items')->firstOrFail();

    expect($order->total_cents)->toBe((14999 * 2) + 4999)
        ->and($order->items)->toHaveCount(2);

    assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $headphones->id,
        'quantity' => 2,
    ]);

    assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $hub->id,
        'quantity' => 1,
    ]);
});

it('completes a shop to checkout journey with catalog products', function (): void {
    $electronics = Category::query()->where('slug', 'electronics')->firstOrFail();
    $headphones = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();
    $hub = Product::query()->where('slug', 'usb-c-hub')->firstOrFail();

    get(route('catalog.shop', ['category' => $electronics->id]))
        ->assertOk()
        ->assertSee('Wireless Headphones')
        ->assertSee('USB-C Hub')
        ->assertDontSee('All-Weather Floor Mats');

    Livewire::test(CreateOrder::class)
        ->assertSet('products', fn (array $products): bool => collect($products)->pluck('id')->contains($headphones->id)
            && collect($products)->pluck('id')->contains($hub->id))
        ->set('quantities.' . $headphones->id, 1)
        ->set('quantities.' . $hub->id, 1)
        ->set('customerName', 'Journey Tester')
        ->set('customerEmail', 'journey@example.com')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('order.view', ['orderHash' => Order::query()->firstOrFail()->order_hash]));

    expect(Order::query()->firstOrFail()->total_cents)->toBe(14999 + 4999);
});

it('decrements product stock when an order is created', function (): void {
    $product = Product::query()->where('slug', 'usb-c-hub')->firstOrFail();
    $startingStock = $product->stock;

    app(CreateOrderAction::class)->execute(
        customerName: 'Stock Tester',
        customerEmail: 'stock@example.com',
        customerPhone: null,
        shippingAddress: null,
        lines: [new OrderItemDto(productId: $product->id, quantity: 2)],
    );

    expect($product->fresh()->stock)->toBe($startingStock - 2);
});

it('snapshots product data on order items instead of linking to catalog models', function (): void {
    $product = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();

    app(CreateOrderAction::class)->execute(
        customerName: 'Snapshot Tester',
        customerEmail: 'snapshot@example.com',
        customerPhone: null,
        shippingAddress: null,
        lines: [new OrderItemDto(productId: $product->id, quantity: 1)],
    );

    $product->update([
        'name' => 'Renamed Product',
        'price_cents' => 1,
    ]);

    $item = OrderItem::query()->firstOrFail();

    expect($item->product_name)->toBe('Wireless Headphones')
        ->and($item->unit_price_cents)->toBe(14999)
        ->and($item->product_id)->toBe($product->id);
});

it('creates orders using core contracts without importing the catalog module', function (): void {
    $product = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();

    $catalog = app(ProductCatalogInterface::class);
    $stock = app(ProductStockInterface::class);

    expect($catalog->findById($product->id)?->name)->toBe('Wireless Headphones')
        ->and($stock->hasStock($product->id, 1))->toBeTrue();

    app(CreateOrderAction::class)->execute(
        customerName: 'Boundary Tester',
        customerEmail: 'boundary@example.com',
        customerPhone: null,
        shippingAddress: null,
        lines: [new OrderItemDto(productId: $product->id, quantity: 1)],
    );

    expect(Order::query()->count())->toBe(1);
});

it('does not reference catalog code inside the order module application code', function (): void {
    $paths = File::allFiles(base_path('Modules/Order/app'));

    foreach ($paths as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        expect($file->getContents())->not->toContain('Modules\\Catalog\\');
    }
});

it('requires at least one product when checking out', function (): void {
    Livewire::test(CreateOrder::class)
        ->set('customerName', 'No Items')
        ->set('customerEmail', 'no-items@example.com')
        ->call('submit')
        ->assertHasErrors(['quantities']);
});

it('rejects checkout when stock is insufficient', function (): void {
    $product = Product::query()->where('slug', 'wireless-headphones')->firstOrFail();

    Livewire::test(CreateOrder::class)
        ->set('quantities.' . $product->id, $product->stock + 1)
        ->set('customerName', 'Too Many')
        ->set('customerEmail', 'too-many@example.com')
        ->call('submit')
        ->assertHasErrors(['quantities']);
});
