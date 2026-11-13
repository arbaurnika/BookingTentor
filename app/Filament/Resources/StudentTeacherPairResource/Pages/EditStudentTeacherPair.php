<?php

namespace App\Filament\Resources\StudentTeacherPairResource\Pages;

use App\Filament\Resources\StudentTeacherPairResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentTeacherPair extends EditRecord
{
    protected static string $resource = StudentTeacherPairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
