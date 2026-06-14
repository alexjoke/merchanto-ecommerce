<?php

namespace Modules\Order\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Order\Models\OrderItem;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order items';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_name')
                    ->label('Product'),
                TextColumn::make('quantity')
                    ->numeric(),
                TextColumn::make('unit_price_cents')
                    ->label('Unit price')
                    ->money('USD', divideBy: 100),
                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->state(fn (OrderItem $record): int => $record->subtotalCents())
                    ->money('USD', divideBy: 100),
            ])
            ->paginated(false);
    }
}
