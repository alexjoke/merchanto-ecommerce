<?php

namespace Modules\Order\Filament\Resources\OrderResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Actions\UpdateOrderStatusAction;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Exceptions\OrderException;
use Modules\Order\Filament\Resources\OrderResource;
use Modules\Order\Models\Order;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Order $record */
        $newStatus = OrderStatus::from($data['status']);

        if ($record->status !== $newStatus) {
            try {
                app(UpdateOrderStatusAction::class)->execute($record, $newStatus);
            } catch (OrderException $exception) {
                $this->notifyOrderError($exception->getMessage());
                $this->refreshFormData(['status']);
                $this->halt(shouldRollbackDatabaseTransaction: true);
            }
        }

        return $record->fresh('items');
    }

    private function notifyOrderError(string $message): void
    {
        Notification::make()
            ->danger()
            ->title('Unable to update order')
            ->body($message)
            ->send();

        $this->addError('data.status', $message);
    }
}
