<?php

namespace App\Filament\Resources\AvailabilitySlotResource\Pages;

use App\Filament\Resources\AvailabilitySlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvailabilitySlot extends EditRecord
{
    protected static string $resource = AvailabilitySlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
