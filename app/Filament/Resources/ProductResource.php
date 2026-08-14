<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Filament\Resources\ProductResource\RelationManagers\ProductOptionsRelationManager;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = '商品';

    protected static ?string $modelLabel = '商品';

    protected static ?string $pluralModelLabel = '商品';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('分類')
                    ->options(fn () => Category::orderBy('sort_order')->pluck('name', 'id'))
                    ->live()
                    ->dehydrated(false)
                    ->afterStateHydrated(function (Set $set, $record) {
                        if ($record?->subcategory) {
                            $set('category_id', $record->subcategory->category_id);
                        }
                    })
                    ->afterStateUpdated(fn (Set $set) => $set('subcategory_id', null))
                    ->required(),
                Forms\Components\Select::make('subcategory_id')
                    ->label('子分類')
                    ->options(function (Get $get) {
                        $categoryId = $get('category_id');

                        if (! $categoryId) {
                            return [];
                        }

                        return Subcategory::where('category_id', $categoryId)
                            ->orderBy('sort_order')
                            ->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }

                        $slug = Str::slug($state ?? '', '-');
                        $set('slug', $slug !== '' ? $slug : Str::random(8));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('網址代稱')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TiptapEditor::make('description')
                    ->label('商品描述')
                    ->tools(['bold', 'italic', 'underline', 'bullet-list', 'ordered-list', 'hr', 'link', 'oembed', '|', 'color', 'highlight'])
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('啟用')
                    ->required()
                    ->default(true),
                Forms\Components\Toggle::make('is_featured')
                    ->label('設為精選商品')
                    ->live()
                    ->default(false),
                Forms\Components\TextInput::make('featured_sort')
                    ->label('精選排序')
                    ->numeric()
                    ->visible(fn (Get $get) => $get('is_featured')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subcategory.name')
                    ->label('子分類')
                    ->default('未分類'),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('啟用')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('精選')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subcategory_id')
                    ->label('子分類')
                    ->options(fn () => Subcategory::orderBy('sort_order')->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn (Product $record) => "刪除「{$record->name}」"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductOptionsRelationManager::class,
            ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
