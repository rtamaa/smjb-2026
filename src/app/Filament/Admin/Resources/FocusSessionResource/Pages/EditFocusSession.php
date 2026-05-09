<?php

namespace App\Filament\Admin\Resources\FocusSessionResource\Pages;

use App\Filament\Admin\Resources\FocusSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFocusSession extends EditRecord
{
    protected static string $resource = FocusSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
