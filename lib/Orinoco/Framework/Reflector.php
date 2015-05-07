<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Reflector
{
    // reflection store
    private $reflection;
    // class name
    private $class;

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
     * Create an instance of ReflectionClass
     *
     * @param String $class (class name)
     * @return void     
     */
    public function reflectionLoad($class)
    {
        $this->class = $class;
        $this->reflection = new \ReflectionClass($class);
    }

    /**
     * Get method's number of parameter(s)
     *
     * @param String $method_name
     * @return Integer Number of parameters
     */
    private function reflectionGetParameterCount($method_name)
    {
        return $this->reflection->getMethod($method_name)->getNumberOfParameters();
    }

    /**
     * Create an instance of ReflectionParameter
     *
     * @param String $method_name
     * @param Integer $index (parameter index)
     * @return ReflectionParameter object
     */
    private function reflectionParameterInfo($method_name, $index)
    {
        return new \ReflectionParameter(array($this->class, $method_name), $index);
    }

    /**
     * Get method's parameters/dependencies
     *
     * @param String $method_name
     * @return Array Parameters/dependencies
     */
    public function reflectionGetMethodDependencies($method_name)
    {
        $count = $this->reflectionGetParameterCount($method_name);
        if ($count === 0) {
            return array();
        } else {
            $count--;
            $dependencies = array();
            for ($c = 0; $c <= $count; $c++) {
                $info = $this->reflectionParameterInfo($method_name, $c);

                // check if dependency is a class object or part of URL segment
                if (isset($info->getClass()->name)) {
                    $dependency = $info->getClass()->name;
                } else {
                    $dependency = $info->name;
                }

                // get required dependency from Registry
                if ($d = $this->resolve($dependency)) {
                    $dependencies[] = $d;
                }
            }
            return $dependencies;
        }
    }

    /**
     * Create an instance w/ or w/o arguments/parameters
     *
     * @param Array $arguments (parameters/dependencies)
     * @param Boolean $with_constructor create instance w/ or w/o __construct being executed
     * @return Object instance
     */
    public function reflectionCreateInstance($arguments = array(), $with_constructor = true)
    {
        if ($with_constructor) {
            // create new instance w/ __construct executed
            return $this->reflection->newInstanceArgs($arguments);
        } else {
            // create new instance W/O __construct being executed
            return $this->reflection->newInstanceWithoutConstructor($arguments);
        }
    }
}
