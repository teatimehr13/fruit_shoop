<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\SubcategoriesRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '商品分類';

    protected static ?string $modelLabel = '分類';

    protected static ?string $pluralModelLabel = '分類';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('啟用')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('啟用')
                    ->boolean(),
                Tables\Columns\TextColumn::make('subcategories_count')
                    ->counts('subcategories')
                    ->label('子分類數'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn (Category $record) => "刪除「{$record->name}」")
                    ->before(function (Category $record, Tables\Actions\DeleteAction $action) {
                        if ($record->subcategories()->count() > 0) {
                            Notification::make()
                                ->title('無法刪除:此分類底下仍有子分類')
                                ->danger()
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $blocked = $records->filter(fn (Category $record) => $record->subcategories()->count() > 0);

                            if ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->title('無法刪除:' . $blocked->pluck('name')->implode('、') . ' 底下仍有子分類')
                                    ->danger()
                                    ->send();
                            }

                            $records->diff($blocked)->each(fn (Category $record) => $record->delete());
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubcategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
