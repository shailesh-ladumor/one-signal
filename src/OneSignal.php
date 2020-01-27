<?php

namespace Ladumor\OneSignal;

use Illuminate\Support\Facades\Facade;

class OneSignal extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'one-signal';
    }
}
