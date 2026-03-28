<?php

namespace App\Filament\Admin\Resources\GoverningBodyMemberResource\Pages;

use App\Filament\Admin\Resources\GoverningBodyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGoverningBodyMember extends EditRecord
{
    protected static string $resource = GoverningBodyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
