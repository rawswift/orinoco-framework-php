<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Http
{
    // $_SERVER storage
    private $server;

    /**
     * Constructor, setup properties
     *
     * @param $_SERVER variable $server
     * @return void
     */
    public function __construct($server)
    {
        $this->server = $server;
    }

    /**
     * Return the $_SERVER['REQUEST_URI']
     *
     * @return string; request URI
     */
    public function getRequestURI() {
        return $this->server['REQUEST_URI'];
    }

    /**
     * Return $server ($_SERVER) variable
     *
     * @return array $server ($_SERVER)
     */
    public function getServerInfo() {
        return $this->server;
    }

    /**
     * Get value from $_SERVER array
     *
     * @param key name $name
     * @return string (value);
     */
    public function getValue($name) {
        $name = strtoupper($name);
        if (isset($this->server[$name])) {
            return $this->server[$name];
        }
        return false;
    }

    /**
     * Set HTTP header (response)
     *
     * @return void
     */
    public function setHeader($header, $replace = true, $http_response_code = null)
    {
        // check if $header is an array
        if (is_array($header)) {
            foreach ($header as $k => $v) {
                header($k . ": " . $v, $replace, $http_response_code);
            }
        // else, assume $header is a string
        } else {
            header($header, $replace, $http_response_code);
        }
    }
}
