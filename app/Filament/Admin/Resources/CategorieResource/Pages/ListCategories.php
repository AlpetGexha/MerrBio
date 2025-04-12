<?php

namespace App\Filament\Admin\Resources\CategorieResource\Pages;

use App\Filament\Admin\Resources\CategorieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategorieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
