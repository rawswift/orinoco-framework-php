<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Registry extends Reflector
{
    // container
    protected $container;

    /**
     * Constructor, nothing to do, as of now
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Register/map object or segments
     *
     * @param object/array $mixed
     * @return object/array
     */
    public function register($mixed)
    {
        if (is_object($mixed)) {
            if (!isset($this->container[get_class($mixed)])) {
                // store object
                $this->container[get_class($mixed)] = $mixed;
                return $mixed;
            }
        } else if (is_array($mixed)) {
            // iterate and store key/value pair
            foreach ($mixed as $k => $v) {
                $this->container[$k] = $v;
            }
            return true;
        }
        return false;
    }    

    /**
     * Get/resolve registered object or segment, by name
     *
     * @param string $name
     * @return object, mixed or boolean
     */
    public function resolve($name)
    {
        if (isset($this->container[$name])) {
            return $this->container[$name];
        }
        return false;
    }
}
