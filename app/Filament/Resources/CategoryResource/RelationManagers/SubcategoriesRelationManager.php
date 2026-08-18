<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class SubcategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'subcategories';

    protected static ?string $title = '子分類';

    protected static ?string $modelLabel = '子分類';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('名稱')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        ignoreRecord: true,
                        modifyRuleUsing: fn ($rule) => $rule->where('category_id', $this->getOwnerRecord()->id),
                    ),
                Forms\Components\Toggle::make('is_enabled')
                    ->label('啟用')
                    ->required()
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名稱')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label('啟用')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('新增子分類')
                    ->mutateFormDataUsing(function (array $data) {
                        $categoryId = $this->getOwnerRecord()->id;

                        $data['sort_order'] = DB::transaction(function () use ($categoryId) {
                            return (Subcategory::where('category_id', $categoryId)
                                ->lockForUpdate()
                                ->max('sort_order') ?? 0) + 1;
                        });

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn (Subcategory $record) => "刪除「{$record->name}」"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
