<?php

namespace App\Filament\Resources\MembershipLevelResource\Pages;

use App\Filament\Resources\MembershipLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMembershipLevel extends EditRecord
{
    protected static string $resource = MembershipLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
