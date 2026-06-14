<?php

namespace Modules\Catalog\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Modules\Core\Contracts\ProductCatalogInterface;
use Modules\Core\DTO\ProductDto;

#[Layout('catalog::layouts.shop')]
class ProductBrowser extends Component
{
    #[Url(as: 'category')]
    public ?int $categoryId = null;

    /**
     * @var array<int, array{id: int, name: string, slug: string}>
     */
    public array $categories = [];

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
        $this->categories = $catalog->listCategories()
            ->map(fn ($category) => $category->toArray())
            ->values()
            ->all();

        $this->loadProducts($catalog);
    }

    public function updatedCategoryId(ProductCatalogInterface $catalog): void
    {
        $this->loadProducts($catalog);
    }

    public function clearCategoryFilter(ProductCatalogInterface $catalog): void
    {
        $this->categoryId = null;
        $this->loadProducts($catalog);
    }

    public function render(): View
    {
        return view('catalog::livewire.product-browser');
    }

    private function loadProducts(ProductCatalogInterface $catalog): void
    {
        $this->products = $catalog->listAvailable($this->categoryId)
            ->map(fn (ProductDto $product): array => $product->toArray())
            ->values()
            ->all();
    }
}
