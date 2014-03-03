<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Registry extends Reflector
{
    // class registry (container/map)
    protected $registry;

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
     * Register/map class object
     *
     * @param object $obj
     * @return object
     */
    public function register($obj)
    {
        if (!isset($this->registry[get_class($obj)])) {
            $this->registry[get_class($obj)] = $obj;
            return $obj;
        }
        return false;
    }

    /**
     * Get/resolve registered class, by name
     *
     * @param string $class_name
     * @return string or boolean
     */
    public function resolve($class_name)
    {
        if (isset($this->registry[$class_name])) {
            return $this->registry[$class_name];
        }
        return false;
    }
}
