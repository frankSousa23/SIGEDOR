<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

/**
 * Widget de Tareas Pendientes y Usuarios Recientes.
 */
class TasksOverview extends BaseWidget
{
    protected static ?string $heading = 'Usuarios Pendientes / Recientes';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Admin ve usuarios pendientes; Jefe de Área ve docentes activos de su sede.
        return auth()->user()?->hasAnyRole(['admin', 'area_manager']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),

                TextColumn::make('is_approved')
                    ->label('Estado de Aprobación')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aprobado' : 'Pendiente'),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();

        if (! $user) {
            return User::query()->whereRaw('1 = 0');
        }

        if ($user->hasRole('admin')) {
            return User::query()->latest();
        }

        if ($user->hasRole('area_manager')) {
            return User::query()
                ->role('teacher')
                ->where('sede_id', $user->sede_id)
                ->where('is_active', true)
                ->latest();
        }

        return User::query()->where('id', $user->id);
    }
}
