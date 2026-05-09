<?php

namespace App\Filament\Admin\Resources\ReminderResource\Pages;

use App\Filament\Admin\Resources\ReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReminders extends ListRecords
{
    protected static string $resource = ReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
