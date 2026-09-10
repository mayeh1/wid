<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    private array $rolesBeforeSave = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $this->rolesBeforeSave = $this->record->roles->pluck('name')->sort()->values()->all();
    }

    protected function afterSave(): void
    {
        $rolesAfterSave = $this->record->fresh()->roles->pluck('name')->sort()->values()->all();

        if ($this->rolesBeforeSave !== $rolesAfterSave) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($this->record)
                ->withProperties(['old' => $this->rolesBeforeSave, 'new' => $rolesAfterSave])
                ->log('roles updated');
        }
    }
}
