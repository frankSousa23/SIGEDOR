<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RuntimeException;

class EnvCheck extends Command
{
    protected $signature = 'env:check';

    protected $description = 'Verifica la configuración del entorno';

    public function handle()
    {
        $this->checkLaravelVersion('11.41.3');
        $this->checkPackageVersion('filament/filament', '^3.2');
        $this->checkDatabaseConnection('sqlite');
    }

    private function checkDatabaseConnection($driver)
    {
        throw_unless(
            config('database.default') === $driver,
            new RuntimeException("Controlador de BD debe ser $driver")
        );
    }
}
