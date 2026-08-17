<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('changeStatus')
                ->label('變更狀態')
                ->icon('heroicon-o-arrow-path')
                ->form(OrderResource::changeStatusFormSchema())
                ->fillForm(fn (Order $record): array => ['order_status' => $record->order_status])
                ->action(OrderResource::changeStatusUsing()),
        ];
    }
}
