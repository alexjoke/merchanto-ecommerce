<?php

namespace Modules\Catalog\Services;

use Illuminate\Support\Collection;
use Modules\Catalog\Models\Category;
use Modules\Catalog\Models\Product;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\DTO\CategoryDto;
use Modules\Core\DTO\ProductDto;

class ProductCatalogService implements ProductCatalogInterface
{
    public function findById(int $productId): ?ProductDto
    {
        $product = Product::query()
            ->with('category')
            ->find($productId);

        if ($product === null) {
            return null;
        }

        return $this->toProductDto($product);
    }

    public function listAvailable(?int $categoryId = null): Collection
    {
        return Product::query()
            ->available()
            ->when($categoryId !== null, fn ($query) => $query->where('category_id', $categoryId))
            ->with('category')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product): ProductDto => $this->toProductDto($product));
    }

    public function listCategories(): Collection
    {
        return Category::query()
            ->whereHas('products', function ($query): void {
                $query->where('is_published', true)->where('stock', '>', 0);
            })
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): CategoryDto => new CategoryDto(
                id: $category->id,
                name: $category->name,
                slug: $category->slug,
            ));
    }

    private function toProductDto(Product $product): ProductDto
    {
        return new ProductDto(
            id: $product->id,
            name: $product->name,
            description: $product->description ?? '',
            priceCents: $product->price_cents,
            stock: $product->stock,
            categoryId: $product->category_id,
            categoryName: $product->category?->name,
        );
    }
}
