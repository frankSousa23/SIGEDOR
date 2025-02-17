<?php

namespace App\Filament\Resources\DedicationResource\Pages;

use App\Filament\Resources\DedicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class CreateDedication extends CreateRecord
{
    protected static string $resource = DedicationResource::class;
}
