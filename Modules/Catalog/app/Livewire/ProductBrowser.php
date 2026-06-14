<?php

namespace Modules\Catalog\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\DTO\ProductDto;

#[Layout('catalog::layouts.shop')]
class ProductBrowser extends Component
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

    public function mount(ProductCatalogInterface $catalog): void
    {
        $this->products = $catalog->listAvailable()
            ->map(fn (ProductDto $product): array => $product->toArray())
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view('catalog::livewire.product-browser');
    }
}
