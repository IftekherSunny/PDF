<?php

namespace Sun\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sun\PDF
 */
class PDFFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Sun\Contract\PDF';
    }
}
