<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Site;

class CreateSite extends CreateRecord
{
    protected static string $resource = SiteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    if (Site::where('teacher_id', $data['teacher_id'])->exists()) {
        throw new \Exception('Este docente ya tiene una sede asignada.');
    }

    return $data;
}
}


