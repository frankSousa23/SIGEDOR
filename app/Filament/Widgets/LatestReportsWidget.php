<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestReportsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Últimos Reportes y Memorandos Emitidos';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(
                Report::query()
                    ->latest()
                    ->when($user && $user->hasRole('area_manager') && ! $user->hasRole('admin') && $user->sede_id, function (Builder $q) use ($user) {
                        $q->where('sede_id', $user->sede_id)
                            ->orWhereHas('teacher', fn ($sub) => $sub->where('sede_id', $user->sede_id));
                    })
                    ->when($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['admin', 'area_manager']), function (Builder $q) use ($user) {
                        $teacherCdi = $user->teacher?->cdi;
                        if ($teacherCdi) {
                            $q->where('teacher_cdi', $teacherCdi);
                        } else {
                            $q->whereHas('teacher', fn ($sub) => $sub->where('user_id', $user->id));
                        }
                    })
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('memoNumber')
                    ->label('Nº Memo')
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('typeReport')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Constancia de Trabajo' => 'success',
                        'Memorando Administrativo' => 'primary',
                        'Informe de Escalafón' => 'warning',
                        'Informe de Dedicación' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('teacher.full_name')
                    ->label('Docente'),

                TextColumn::make('sede.nombre')
                    ->label('Sede'),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Report $record) {
                        $pdf = Pdf::loadView('pdf.report', ['report' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "reporte_{$record->memoNumber}.pdf"
                        );
                    }),
            ]);
    }
}
