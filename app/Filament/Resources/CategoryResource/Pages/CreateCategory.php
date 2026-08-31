<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $rule = $data['direct_promotion_rule'] ?? 'none';

        if ($rule === 'specialty_master') {
            $data['instructor'] = now()->toDateString();
            $data['asistente'] = now()->toDateString();
        } elseif ($rule === 'doctorate') {
            $data['instructor'] = now()->toDateString();
            $data['asistente'] = now()->toDateString();
            $data['agregado'] = now()->toDateString();
        }

        // We already dehydrated(false) the field in the form, but just in case, we unset it so it doesn't break SQL insert
        unset($data['direct_promotion_rule']);

        return $data;
    }
}
