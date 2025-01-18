<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class TasksOverview extends BaseWidget
{
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->label('Correo')
                ->searchable(),

            BadgeColumn::make('is_approved')
                ->label('Estado')
                ->colors([
                    'danger' => false,
                    'success' => true,
                ])
                ->formatStateUsing(fn (bool $state): string => match ($state) {
                    true => 'Aprobado',
                    false => 'Pendiente',
                }),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return User::query()
                ->where('is_approved', false)
                ->latest();
        }

        if ($user->hasRole('area_manager')) {
            return User::query()
                ->role('teacher')
                ->where('site_id', $user->site_id)
                ->where('is_active', true)
                ->latest();
        }

        return User::where('id', $user->id);
    }
}
