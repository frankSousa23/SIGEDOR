<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $rule = $data['direct_promotion_rule'] ?? 'none';

        if ($rule === 'specialty_master') {
            $data['instructor'] = $data['instructor'] ?? now()->toDateString();
            $data['asistente'] = $data['asistente'] ?? now()->toDateString();
        } elseif ($rule === 'doctorate') {
            $data['instructor'] = $data['instructor'] ?? now()->toDateString();
            $data['asistente'] = $data['asistente'] ?? now()->toDateString();
            $data['agregado'] = $data['agregado'] ?? now()->toDateString();
        }

        unset($data['direct_promotion_rule']);

        return $data;
    }
}
