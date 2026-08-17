<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected static ?string $title = '新增分類';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sort_order'] = DB::transaction(function () {
            return (Category::lockForUpdate()->max('sort_order') ?? 0) + 1;
        });

        return $data;
    }
}
