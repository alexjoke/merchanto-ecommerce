<?php

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Catalog\Filament\Resources\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public static function priceToCents(mixed $price): int
    {
        return (int) round(((float) $price) * 100);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['price_cents'] = self::priceToCents($this->form->getRawState()['price_dollars'] ?? 0);

        return $data;
    }
}
