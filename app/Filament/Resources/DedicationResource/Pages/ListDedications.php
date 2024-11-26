<?php

namespace App\Filament\Resources\DedicationResource\Pages;

use App\Filament\Resources\DedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDedications extends ListRecords
{
    protected static string $resource = DedicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
