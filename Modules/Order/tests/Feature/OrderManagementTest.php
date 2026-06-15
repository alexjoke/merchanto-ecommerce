<?php

namespace Modules\Order\Tests\Feature;

use Livewire\Livewire;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Resources\OrderResource\Pages\EditOrder;
use Modules\Order\Filament\Resources\OrderResource\Pages\ListOrders;
use Modules\Order\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use Modules\Order\Livewire\ViewOrder;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

use function Pest\Laravel\get;

function createAdminTestOrder(): Order
{
    $order = Order::query()->create([
        'order_hash' => 'ORD-TEST0001',
        'customer_name' => 'Admin Test Customer',
        'customer_email' => 'admin-test@example.com',
        'customer_phone' => null,
        'shipping_address' => '456 Admin Ave',
        'status' => OrderStatus::Pending,
        'total_cents' => 14999,
    ]);

    OrderItem::query()->create([
        'order_id' => $order->id,
        'product_id' => 1,
        'product_name' => 'Wireless Headphones',
        'unit_price_cents' => 14999,
        'quantity' => 1,
    ]);

    return $order;
}

beforeEach(function (): void {
    seedCatalog();
});

it('allows guests to view an order by hash', function (): void {
    createAdminTestOrder();

    get(route('order.view', ['orderHash' => 'ORD-TEST0001']))
        ->assertOk()
        ->assertSee('ORD-TEST0001')
        ->assertSee('Wireless Headphones')
        ->assertSee('Admin Test Customer');
});

it('returns not found for an unknown order hash', function (): void {
    get(route('order.view', ['orderHash' => 'ORD-MISSING']))
        ->assertNotFound();
});

it('loads order details in the view order livewire component', function (): void {
    createAdminTestOrder();

    Livewire::test(ViewOrder::class, ['orderHash' => 'ORD-TEST0001'])
        ->assertSet('order.orderHash', 'ORD-TEST0001')
        ->assertSet('order.customerName', 'Admin Test Customer')
        ->assertSee('Wireless Headphones');
});

it('lists orders in admin', function (): void {
    $order = createAdminTestOrder();

    actingAsAdmin();

    Livewire::test(ListOrders::class)
        ->assertCanSeeTableRecords([$order])
        ->assertSee('ORD-TEST0001')
        ->assertSee('Admin Test Customer');
});

it('updates order status in admin', function (): void {
    $order = createAdminTestOrder();

    actingAsAdmin();

    fillAdminForm(Livewire::test(EditOrder::class, ['record' => $order->getKey()]), [
        'status' => OrderStatus::Confirmed->value,
    ])
        ->call('save')
        ->assertHasNoFormErrors();

    $order->refresh();

    expect($order->status)->toBe(OrderStatus::Confirmed);
});

it('prevents invalid order status transitions in admin', function (): void {
    $order = createAdminTestOrder();

    actingAsAdmin();

    $order->update(['status' => OrderStatus::Delivered]);

    fillAdminForm(Livewire::test(EditOrder::class, ['record' => $order->getKey()]), [
        'status' => OrderStatus::Pending->value,
    ])
        ->call('save');

    $order->refresh();

    expect($order->status)->toBe(OrderStatus::Delivered);
});

it('displays order items in the admin relation manager', function (): void {
    $order = createAdminTestOrder();

    actingAsAdmin();

    $item = OrderItem::query()->where('order_id', $order->id)->firstOrFail();

    Livewire::test(ItemsRelationManager::class, [
        'ownerRecord' => $order,
        'pageClass' => EditOrder::class,
    ])
        ->assertCanSeeTableRecords([$item])
        ->assertSee('Wireless Headphones');
});
