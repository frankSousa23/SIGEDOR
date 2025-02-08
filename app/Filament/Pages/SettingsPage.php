<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class SettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.pages.settings';

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);
    }
}
