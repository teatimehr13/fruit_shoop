<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductImage;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ProductImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'productImages';

    protected static ?string $title = '商品圖片';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('圖片')
                    ->disk('public')
                    ->extraImgAttributes([
                        'style' => 'cursor: zoom-in;',
                        'x-on:mouseenter' => '(new Image()).src = $el.src',
                        'x-on:click' => 'window.dispatchEvent(new CustomEvent("open-lightbox", { detail: { url: $el.src } }))',
                    ]),
                Tables\Columns\TextInputColumn::make('alt_text')
                    ->label('替代文字'),
                Tables\Columns\IconColumn::make('is_primary')
                    ->label('主圖')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('upload')
                    ->label('新增圖片')
                    ->icon('heroicon-o-plus')
                    ->form([
                        Forms\Components\FileUpload::make('images')
                            ->label('圖片')
                            ->multiple()
                            ->disk('public')
                            ->directory(fn () => 'products/' . $this->getOwnerRecord()->id)
                            ->image()
                            ->imagePreviewHeight('150')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $product = $this->getOwnerRecord();

                        DB::transaction(function () use ($product, $data) {
                            $baseQuery = $product->productImages()->lockForUpdate();
                            $hasAny = (clone $baseQuery)->exists();
                            $nextOrder = (clone $baseQuery)->max('sort_order') ?? 0;

                            foreach (array_values($data['images']) as $i => $path) {
                                $product->productImages()->create([
                                    'image' => $path,
                                    'is_primary' => ! $hasAny && $i === 0,
                                    'sort_order' => ++$nextOrder,
                                ]);
                            }
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('setPrimary')
                    ->label('設為主圖')
                    ->icon('heroicon-o-star')
                    ->visible(fn (ProductImage $record) => ! $record->is_primary)
                    ->action(function (ProductImage $record) {
                        DB::transaction(function () use ($record) {
                            ProductImage::where('product_id', $record->product_id)
                                ->where('id', '!=', $record->id)
                                ->update(['is_primary' => false]);

                            $record->update(['is_primary' => true]);
                        });
                    }),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('刪除這張圖片')
                    ->modalContent(fn (ProductImage $record) => new HtmlString(
                        '<div style="display: flex; justify-content: center;">'
                        . '<img src="' . e($record->img_url) . '" style="max-height: 100px; max-width: 100%; border-radius: 0.5rem; object-fit: contain;" />'
                        . '</div>'
                    ))
                    ->action(function (ProductImage $record) {
                        $productId = $record->product_id;
                        $wasPrimary = $record->is_primary;

                        if (ProductImage::where('product_id', $productId)->count() <= 1) {
                            Notification::make()
                                ->title('產品至少需要保留一張圖片')
                                ->danger()
                                ->send();

                            return;
                        }

                        DB::transaction(function () use ($record, $productId, $wasPrimary) {
                            if ($record->image) {
                                Storage::disk('public')->delete($record->image);
                            }

                            $record->delete();

                            if ($wasPrimary) {
                                ProductImage::where('product_id', $productId)
                                    ->orderBy('sort_order')
                                    ->first()
                                    ?->update(['is_primary' => true]);
                            }
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (Collection $records) {
                            $productId = $this->getOwnerRecord()->id;
                            $remaining = ProductImage::where('product_id', $productId)->count() - $records->count();

                            if ($remaining < 1) {
                                Notification::make()
                                    ->title('產品至少需要保留一張圖片,無法刪除所選圖片')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            DB::transaction(function () use ($records, $productId) {
                                $hadPrimary = $records->contains(fn (ProductImage $record) => $record->is_primary);

                                foreach ($records as $record) {
                                    if ($record->image) {
                                        Storage::disk('public')->delete($record->image);
                                    }

                                    $record->delete();
                                }

                                if ($hadPrimary) {
                                    ProductImage::where('product_id', $productId)
                                        ->orderBy('sort_order')
                                        ->first()
                                        ?->update(['is_primary' => true]);
                                }
                            });
                        }),
                ]),
            ]);
    }
}
