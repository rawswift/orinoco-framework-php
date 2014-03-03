<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Cache
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
     * Check if Opcode cache (APC) is available and enabled
     * 
     * @return bool; whether or not APC is available and enabled
     */
    public function isAPCAvailable()
    {
        if(extension_loaded('apc') && ini_get('apc.enabled')) {
            return true;
        }
        return false;
    }
}
