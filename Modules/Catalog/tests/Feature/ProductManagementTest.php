<?php

namespace Modules\Catalog\Tests\Feature;

use Livewire\Livewire;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\EditCategory;
use Modules\Catalog\Filament\Resources\CategoryResource\Pages\ListCategories;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\CreateProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\EditProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\ListProducts;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function (): void {
    actingAsAdmin();
});

it('creates a category in admin', function (): void {
    fillAdminForm(Livewire::test(CreateCategory::class), [
        'name' => 'Books',
        'slug' => 'books',
    ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('catalog_categories', [
        'name' => 'Books',
        'slug' => 'books',
    ]);
});

it('updates a category in admin', function (): void {
    $category = Category::query()->create([
        'name' => 'Old Name',
        'slug' => 'old-name',
    ]);

    fillAdminForm(Livewire::test(EditCategory::class, ['record' => $category->getKey()]), [
        'name' => 'Updated Name',
        'slug' => 'updated-name',
    ])
        ->call('save')
        ->assertHasNoFormErrors();

    $category->refresh();

    expect($category->name)->toBe('Updated Name')
        ->and($category->slug)->toBe('updated-name');
});

it('creates a product in admin', function (): void {
    $category = Category::query()->create([
        'name' => 'Electronics',
        'slug' => 'electronics',
    ]);

    fillAdminForm(Livewire::test(CreateProduct::class), [
        'category_id' => $category->id,
        'name' => 'Test Keyboard',
        'slug' => 'test-keyboard',
        'description' => 'Mechanical keyboard',
        'price_dollars' => '79.99',
        'stock' => 15,
        'is_published' => true,
    ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas('catalog_products', [
        'category_id' => $category->id,
        'name' => 'Test Keyboard',
        'slug' => 'test-keyboard',
        'price_cents' => 7999,
        'stock' => 15,
        'is_published' => true,
    ]);
});

it('updates a product in admin', function (): void {
    $product = Product::query()->create([
        'name' => 'Old Product',
        'slug' => 'old-product',
        'description' => 'Old description',
        'price_cents' => 1000,
        'stock' => 5,
        'is_published' => false,
    ]);

    fillAdminForm(Livewire::test(EditProduct::class, ['record' => $product->getKey()]), [
        'name' => 'Updated Product',
        'slug' => 'updated-product',
        'description' => 'Updated description',
        'price_dollars' => '24.50',
        'stock' => 8,
        'is_published' => true,
    ])
        ->call('save')
        ->assertHasNoFormErrors();

    $product->refresh();

    expect($product->name)->toBe('Updated Product')
        ->and($product->price_cents)->toBe(2450)
        ->and($product->stock)->toBe(8)
        ->and($product->is_published)->toBeTrue();
});

it('deletes a product in admin', function (): void {
    $product = Product::query()->create([
        'name' => 'Delete Me',
        'slug' => 'delete-me',
        'price_cents' => 500,
        'stock' => 1,
        'is_published' => true,
    ]);

    Livewire::test(EditProduct::class, ['record' => $product->getKey()])
        ->callAction('delete');

    assertDatabaseMissing('catalog_products', [
        'id' => $product->id,
    ]);
});

it('lists products in admin table', function (): void {
    $alpha = Product::query()->create([
        'name' => 'Alpha Gadget',
        'slug' => 'alpha-gadget',
        'price_cents' => 1000,
        'stock' => 3,
        'is_published' => true,
    ]);

    $beta = Product::query()->create([
        'name' => 'Beta Gadget',
        'slug' => 'beta-gadget',
        'price_cents' => 2000,
        'stock' => 4,
        'is_published' => true,
    ]);

    Livewire::test(ListProducts::class)
        ->assertCanSeeTableRecords([$alpha, $beta]);

    Livewire::test(ListCategories::class)
        ->assertSuccessful();
});

it('deletes a category in admin', function (): void {
    $category = Category::query()->create([
        'name' => 'Remove Me',
        'slug' => 'remove-me',
    ]);

    Livewire::test(EditCategory::class, ['record' => $category->getKey()])
        ->callAction('delete');

    assertDatabaseMissing('catalog_categories', [
        'id' => $category->id,
    ]);
});
