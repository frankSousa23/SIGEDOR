<?php

namespace App\Filament\Resources\DedicationResource\Pages;

use App\Filament\Resources\DedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDedication extends EditRecord
{
    protected static string $resource = DedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
