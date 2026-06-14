<?php

namespace Modules\Core\Contracts;

use Illuminate\Support\Collection;
use Modules\Core\DTO\CategoryDto;
use Modules\Core\DTO\ProductDto;

interface ProductCatalogInterface
{
    public function findById(int $productId): ?ProductDto;

    /** @return Collection<int, ProductDto> */
    public function listAvailable(?int $categoryId = null): Collection;

    /** @return Collection<int, CategoryDto> */
    public function listCategories(): Collection;
}
