<?php

namespace App\Filament\Resources\AvailabilitySlotResource\Pages;

use App\Filament\Resources\AvailabilitySlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvailabilitySlots extends ListRecords
{
    protected static string $resource = AvailabilitySlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
