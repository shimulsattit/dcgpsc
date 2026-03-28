<?php

namespace App\Filament\Admin\Resources\GoverningBodyRoleResource\Pages;

use App\Filament\Admin\Resources\GoverningBodyRoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGoverningBodyRole extends EditRecord
{
    protected static string $resource = GoverningBodyRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
