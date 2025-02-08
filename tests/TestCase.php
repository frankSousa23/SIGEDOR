<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     // Ejecutar los seeders antes de cada test
    //     $this->seed();
    // }
}
