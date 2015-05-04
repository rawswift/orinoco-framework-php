<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

/**
 * Set of methods that perform common, often re-used functions.
 * Utility classes define these common methods under static scope.
 */
class Util
{
    /**
     * Constructor
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
    }
    
    /**
     * var_dump wrapper
     *
     * @return void
     */
    public static function dump($obj)
    {
        var_dump($obj);
    }
}
