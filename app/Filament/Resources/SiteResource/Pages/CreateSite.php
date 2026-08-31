<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use App\Models\Site;
use Filament\Resources\Pages\CreateRecord;

class CreateSite extends CreateRecord
{
    protected static string $resource = SiteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (Site::where('teacher_cdi', $data['teacher_cdi'])->exists()) {
            throw new \Exception('Este docente ya tiene una sede asignada.');
        }

        return $data;
    }
}
