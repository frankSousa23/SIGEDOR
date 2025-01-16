<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Exports\ExportBulkAction;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // Eliminar el botón de exportar a PDF
            // Actions\ExportAction::make()
            //     ->label('Exportar a PDF')
            //     ->color('success')
            //     ->icon('heroicon-o-document-arrow-down')
            //     ->formats([
            //         'pdf',
            //     ]),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            ExportBulkAction::make()
                ->label('Exportar Seleccionados')
                ->formats([
                    'pdf',
                ])
        ];
    }
}
