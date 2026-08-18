<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ProductOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'productOptions';

    protected static ?string $title = '規格選項';

    protected static ?string $modelLabel = '產品選項';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('option_text')
                    ->label('規格')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule) => $rule->where('product_id', $this->getOwnerRecord()->id),
                    ),
                Forms\Components\TextInput::make('original_price')
                    ->label('原價')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$'),
                Forms\Components\TextInput::make('price')
                    ->label('售價')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$'),
                Forms\Components\TextInput::make('inventory')
                    ->label('庫存')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('啟用')
                    ->required()
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('option_text')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('option_text')
                    ->label('規格'),
                Tables\Columns\TextColumn::make('original_price')
                    ->label('原價')
                    ->money('TWD'),
                Tables\Columns\TextColumn::make('price')
                    ->label('售價')
                    ->money('TWD'),
                Tables\Columns\TextColumn::make('inventory')
                    ->label('庫存'),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('啟用')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('新增產品選項')
                    ->mutateFormDataUsing(function (array $data) {
                        $productId = $this->getOwnerRecord()->id;

                        $data['sort_order'] = DB::transaction(function () use ($productId) {
                            return (ProductOption::where('product_id', $productId)
                                ->lockForUpdate()
                                ->max('sort_order') ?? 0) + 1;
                        });

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
