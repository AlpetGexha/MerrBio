<?php

namespace App\Filament\App\Resources\ShippingVendorResource\Pages;

use App\Filament\App\Resources\ShippingVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingVendor extends EditRecord
{
    protected static string $resource = ShippingVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
