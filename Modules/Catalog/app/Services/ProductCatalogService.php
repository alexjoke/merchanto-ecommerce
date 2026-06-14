<?php

namespace Modules\Catalog\Services;

use Illuminate\Support\Collection;
use Modules\Catalog\Models\Product;
use Modules\Core\Contracts\ProductCatalogInterface;
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

    public function listAvailable(): Collection
    {
        return Product::query()
            ->available()
            ->with('category')
            ->orderBy('name')
            ->get()
            ->map(fn (Product $product): ProductDto => $this->toProductDto($product));
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
