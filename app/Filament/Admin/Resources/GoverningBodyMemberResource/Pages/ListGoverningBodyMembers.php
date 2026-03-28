<?php

namespace App\Filament\Admin\Resources\GoverningBodyMemberResource\Pages;

use App\Filament\Admin\Resources\GoverningBodyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoverningBodyMembers extends ListRecords
{
    protected static string $resource = GoverningBodyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
