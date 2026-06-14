<?php

namespace Modules\Core\DTO;

readonly class ProductDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public int $priceCents,
        public int $stock,
        public ?int $categoryId = null,
        public ?string $categoryName = null,
    ) {}

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     description: string,
     *     priceCents: int,
     *     stock: int,
     *     categoryId: int|null,
     *     categoryName: string|null,
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'priceCents' => $this->priceCents,
            'stock' => $this->stock,
            'categoryId' => $this->categoryId,
            'categoryName' => $this->categoryName,
        ];
    }
}
