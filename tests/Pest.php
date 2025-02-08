<?php

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to define your test cases is being bound to a
| PHPUnit\Framework\TestCase instance. Of course, you may define any
| helper methods that can be used by your test cases.
|
*/

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, it's often handy to have access to the
| assertion methods that are available in PHPUnit. Here, we can easily
| register custom expectations to be used with the `expect()` function.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful, you may need to access helpers that aren't
| available as methods. Here, you can define custom helper functions to use
| throughout your test suite.
|
*/

beforeEach(function () {
    Artisan::call('migrate:fresh --seed');
});
