<?php

namespace Modules\Catalog\Tests\Feature;

use Livewire\Livewire;
use Modules\Catalog\Livewire\ProductBrowser;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

use function Pest\Laravel\get;

beforeEach(function (): void {
    seedCatalog();
});

it('loads the public shop page', function (): void {
    get(route('catalog.shop'))
        ->assertOk()
        ->assertSee('Shop');
});

it('displays available published products on the shop page', function (): void {
    get(route('catalog.shop'))
        ->assertSee('Wireless Headphones')
        ->assertSee('USB-C Hub')
        ->assertDontSee('Draft Product');
});

it('lists available products in the product browser component', function (): void {
    Livewire::test(ProductBrowser::class)
        ->assertSet('products', fn (array $products): bool => collect($products)->contains(
            fn (array $product): bool => $product['name'] === 'Wireless Headphones'
        ))
        ->assertSet('categories', fn (array $categories): bool => count($categories) >= 2);
});

it('filters products by category in the product browser', function (): void {
    $electronics = Category::query()->where('slug', 'electronics')->firstOrFail();

    Livewire::test(ProductBrowser::class)
        ->set('categoryId', $electronics->id)
        ->assertSet('products', fn (array $products): bool => collect($products)->every(
            fn (array $product): bool => $product['categoryId'] === $electronics->id
        ))
        ->assertSet('products', fn (array $products): bool => collect($products)->contains(
            fn (array $product): bool => $product['name'] === 'Wireless Headphones'
        ));
});

it('excludes out of stock products from the shop', function (): void {
    Product::query()->create([
        'name' => 'Sold Out Item',
        'slug' => 'sold-out-item',
        'description' => 'No stock left',
        'price_cents' => 1500,
        'stock' => 0,
        'is_published' => true,
    ]);

    Livewire::test(ProductBrowser::class)
        ->assertDontSee('Sold Out Item')
        ->assertSet('products', fn (array $products): bool => collect($products)->doesntContain(
            fn (array $product): bool => $product['name'] === 'Sold Out Item'
        ));
});

it('clears the category filter to show all products again', function (): void {
    $electronics = Category::query()->where('slug', 'electronics')->firstOrFail();

    Livewire::test(ProductBrowser::class)
        ->set('categoryId', $electronics->id)
        ->call('clearCategoryFilter')
        ->assertSet('categoryId', null)
        ->assertSet('products', fn (array $products): bool => count($products) > 1);
});

it('filters products when the category URL query param is present', function (): void {
    $electronics = Category::query()->where('slug', 'electronics')->firstOrFail();

    get(route('catalog.shop', ['category' => $electronics->id]))
        ->assertOk()
        ->assertSee('Wireless Headphones')
        ->assertSee('USB-C Hub')
        ->assertDontSee('All-Weather Floor Mats');
});

it('initializes the product browser with the category from the URL query param', function (): void {
    $electronics = Category::query()->where('slug', 'electronics')->firstOrFail();

    Livewire::withQueryParams(['category' => (string) $electronics->id])
        ->test(ProductBrowser::class)
        ->assertSet('categoryId', $electronics->id)
        ->assertSet('products', fn (array $products): bool => collect($products)->every(
            fn (array $product): bool => $product['categoryId'] === $electronics->id
        ));
});
