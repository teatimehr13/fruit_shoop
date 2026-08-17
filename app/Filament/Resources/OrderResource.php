<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Closure;
use Filament\Forms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = '訂單';

    protected static ?string $modelLabel = '訂單';

    protected static ?string $pluralModelLabel = '訂單';

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('訂單號碼')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('收件人'),
                Tables\Columns\TextColumn::make('shipping_email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('order_status')
                    ->label('狀態')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Order::ALL_ORDER_STATUS_LABELS[$state] ?? '未知狀態'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('金額')
                    ->money('TWD'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('order_status')
                    ->label('訂單狀態')
                    ->options(Order::ORDER_STATUS_LABELS),
                Tables\Filters\SelectFilter::make('range')
                    ->label('時間範圍')
                    ->options([
                        'today' => '今天',
                        '7d' => '近 7 天',
                        '30d' => '近 30 天',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $range = $data['value'] ?? null;
                        $now = now();

                        return match ($range) {
                            'today' => $query->whereDate('created_at', $now->toDateString()),
                            '7d' => $query->where('created_at', '>=', $now->copy()->subDays(7)),
                            '30d' => $query->where('created_at', '>=', $now->copy()->subDays(30)),
                            default => $query,
                        };
                    }),
                Tables\Filters\Filter::make('q')
                    ->label('搜尋')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('訂單號 / Email / 姓名 / 電話'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $q = trim($data['value'] ?? '');

                        return $query->when($q !== '', fn (Builder $query) => $query->where(function (Builder $sub) use ($q) {
                            $sub->where('order_number', 'like', "%{$q}%")
                                ->orWhere('shipping_email', 'like', "%{$q}%")
                                ->orWhere('recipient_name', 'like', "%{$q}%")
                                ->orWhere('recipient_phone', 'like', "%{$q}%");
                        }));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                static::tableChangeStatusAction(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('訂單資訊')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('order_number')->label('訂單號碼'),
                        TextEntry::make('order_status_label')->label('狀態'),
                        TextEntry::make('payment_method')->label('付款方式')->default('-'),
                        TextEntry::make('created_at')->label('建立時間')->dateTime(),
                        TextEntry::make('payment_expire_at_label')
                            ->label('繳費期限')
                            ->visible(fn (Order $record) => $record->is_payment_pending),
                    ]),
                Section::make('收件資訊')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('recipient_name')->label('收件人'),
                        TextEntry::make('recipient_phone')->label('電話'),
                        TextEntry::make('shipping_email')->label('Email'),
                        TextEntry::make('address')
                            ->label('地址')
                            ->state(fn (Order $record) => trim(
                                ($record->shipping_city ?? '')
                                . ($record->shipping_district ?? '')
                                . ($record->shipping_address_detail ?? '')
                            )),
                        TextEntry::make('note')->label('備註')->default('-')->columnSpanFull(),
                    ]),
                Section::make('商品明細')
                    ->schema([
                        RepeatableEntry::make('orderItems')
                            ->label('')
                            ->columns(7)
                            ->schema([
                                ImageEntry::make('image')->hiddenLabel()->width(60)->height(60),
                                TextEntry::make('name')->label('商品')->columnSpan(2),
                                TextEntry::make('option_text')->label('規格'),
                                TextEntry::make('price')->label('單價')->money('TWD'),
                                TextEntry::make('qty')->label('數量'),
                                TextEntry::make('line_total')
                                    ->label('小計')
                                    ->state(fn ($record) => $record->price * $record->qty)
                                    ->money('TWD'),
                            ]),
                    ]),
                Section::make('金額')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('小計')
                            ->state(fn (Order $record) => static::orderSubtotal($record))
                            ->money('TWD'),
                        TextEntry::make('shipping_fee')
                            ->label('運費')
                            ->state(fn (Order $record) => Order::calculateShippingFee(static::orderSubtotal($record)))
                            ->money('TWD'),
                        TextEntry::make('total')
                            ->label('合計')
                            ->state(function (Order $record) {
                                $subtotal = static::orderSubtotal($record);

                                return $subtotal + Order::calculateShippingFee($subtotal);
                            })
                            ->money('TWD'),
                    ]),
            ]);
    }

    private static function orderSubtotal(Order $record): int
    {
        return $record->orderItems->sum(fn ($item) => $item->price * $item->qty);
    }

    public static function changeStatusFormSchema(): array
    {
        return [
            Forms\Components\Select::make('order_status')
                ->label('訂單狀態')
                ->options(Order::ALL_ORDER_STATUS_LABELS)
                ->required(),
        ];
    }

    public static function changeStatusUsing(): Closure
    {
        return function (Order $record, array $data): void {
            $record->update(['order_status' => $data['order_status']]);
        };
    }

    public static function tableChangeStatusAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('changeStatus')
            ->label('變更狀態')
            ->icon('heroicon-o-arrow-path')
            ->form(static::changeStatusFormSchema())
            ->fillForm(fn (Order $record): array => ['order_status' => $record->order_status])
            ->action(static::changeStatusUsing());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
