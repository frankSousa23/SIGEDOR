<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReportsRelationManager extends RelationManager
{
    protected static string $relationship = 'reports';

    protected static ?string $title = 'Reportes y Memorandos';

    protected static ?string $modelLabel = 'Reporte';

    protected static ?string $pluralModelLabel = 'Reportes y Memorandos';

    public function form(Form $form): Form
    {
        return ReportResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('memoNumber')
            ->columns([
                TextColumn::make('memoNumber')
                    ->label('Nº Memo')
                    ->copyable()
                    ->copyMessage('Número de memorando copiado')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('typeReport')
                    ->label('Tipo de Informe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Constancia de Trabajo' => 'success',
                        'Memorando Administrativo' => 'primary',
                        'Informe de Escalafón' => 'warning',
                        'Informe de Dedicación' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'success',
                        'annulled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'issued' => 'Emitido',
                        'annulled' => 'Anulado',
                        default => 'Borrador',
                    }),

                TextColumn::make('verification_code')
                    ->label('Código')
                    ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
