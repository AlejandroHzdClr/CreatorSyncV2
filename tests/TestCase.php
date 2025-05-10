<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Deshabilitar el middleware de CSRF para las pruebas
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
