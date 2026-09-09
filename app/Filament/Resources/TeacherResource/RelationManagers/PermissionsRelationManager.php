<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Filament\Resources\PermissionTeacherResource;
use App\Models\PermissionTeacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissionsTeachers';

    protected static ?string $title = 'Permisos y Licencias';

    protected static ?string $modelLabel = 'Permiso';

    protected static ?string $pluralModelLabel = 'Permisos y Licencias';

    public function form(Form $form): Form
    {
        return PermissionTeacherResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('memo_number')
            ->columns([
                TextColumn::make('memo_number')
                    ->label('Nº Memo')
                    ->copyable()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo de Permiso')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Asunto')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                        default => 'Pendiente',
                    }),

                IconColumn::make('is_paid')
                    ->label('Remunerado')
                    ->boolean(),
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
                    ->action(function (PermissionTeacher $record) {
                        $pdf = Pdf::loadView('pdf.permission', ['permission' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "permiso_{$record->memo_number}.pdf"
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
