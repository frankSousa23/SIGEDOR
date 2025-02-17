<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

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


