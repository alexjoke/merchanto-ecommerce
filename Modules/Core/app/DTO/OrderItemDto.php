<?php

namespace Modules\Core\DTO;

readonly class OrderItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity,
    ) {}
}
