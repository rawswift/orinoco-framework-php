<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Request
{
    // Http object
    public $Http;
    // Route object
    public $Route;

    /**
     * Constructor
     *
     * @param Orinoco\Framework\Http $http
     * @param Orinoco\Framework\Route $route
     * @return void
     */
    public function __construct(Http $http, Route $route)
    {
        $this->Http = $http;
        $this->Route = $route;
    }
}
