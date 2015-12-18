<?php

namespace Sun\Alien;

use Sun\Support\Alien;

/**
 * @see \Sun\PDF
 */
class PDFAlien extends Alien
{
    /**
     * To register Alien
     *
     * @return string namespace
     */
    public static function registerAlien()
    {
        return 'Sun\Contract\PDF';
    }
}
