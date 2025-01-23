<?php

namespace GenioForge\Consumer;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ConsumerServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Consumer' => ConsumerFacade::class
        ];
    }
}
