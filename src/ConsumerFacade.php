<?php

namespace GenioForge\Consumer;

use Illuminate\Support\Facades\Facade;

class ConsumerFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'consumer';
    }
}
