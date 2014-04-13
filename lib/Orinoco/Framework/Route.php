<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Route
{
    // route table (map)
    public $route_table;
    // contains the raw URI request e.g. /foo/bar?id=123
    public $request_uri;
    // contains the parsed URL components
    public $components;
    // contains the actual controller and action (array)
    public $request_map;
    // controller name
    public $controller;
    // action name
    public $action;
    // controller class path
    public $path;
    // URI segments storage (e.g. /foo/:name/:id)
    public $segments = array();
    // Registry instance
    private $registry;

    /**
     * Constructor, setup properties
     *
     * @param request URI $request_uri
     * @return void
     */
    public function __construct(Http $http, Registry $registry)
    {
        $this->request_uri = $http->getRequestURI();
        $this->registry = $registry;
    }

    /**
     * Set/add properties to route table (map)
     *
     * @param string regular expression string $uri
     * @param array property $method_map
     * @return void
     */
    public function setRoute($uri, $method_map)
    {
        $this->route_table[trim($uri)] = $method_map;
    }

    /**
     * Return the route table (array)
     *
     * @return array route table
     */
    public function getRouteTable()
    {
        return $this->route_table;
    }

    /**
     * Parse request URI
     *
     * @return bool; whether or not we have a matching route
     */
    public function parseRequest()
    {
        $this->components = parse_url($this->request_uri);
        $this->request_map = preg_split("/\//", $this->components['path'], 0, PREG_SPLIT_NO_EMPTY);
        if ($match = $this->matchRouteRule($this->components['path'])) {
            $this->controller = ($match["controller"] === SELF_CONTROLLER) ? $this->request_map[0] : $match["controller"];
            $this->action = ($match["action"] === SELF_ACTION) ? $this->request_map[1] : $match["action"];
            if (isset($match["path"])) {
                $this->path = $match["path"];
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the controller name
     *
     * @return bool|string
     */
    public function getController()
    {
        return isset($this->controller) ? $this->controller : false;
    }

    /**
     * Return the action name
     *
     * @return bool|string
     */
    public function getAction()
    {
        return isset($this->action) ? $this->action : false;
    }

    /**
     * Check if controller class path is defined
     *
     * @return bool|string
     */
    public function isPathDefined()
    {
        if (isset($this->path)) {
            // make sure there's no slashes on front (left) 
            return ltrim($this->path, "/");
        }
        return false;
    }

    /**
     * Match request
     *
     * @return bool|array
     */
    private function matchRouteRule($subject)
    {
        foreach($this->route_table as $k => $v) {
            $filters = array();
            if (isset($v['filters'])) {
                $filters = $v['filters'];
            }
            $callback = function($matches) use ($filters) {
                if (isset($matches[1]) && isset($filters[$matches[1]])) {
                    return $filters[$matches[1]];
                }
                return '(\w+)';
            };
            $pattern = "@^" . preg_replace_callback("/:(\w+)/", $callback, $k) . "$@i";
            $matches = array();
            if (preg_match($pattern, $subject, $matches)) {
                if(strpos($k, ':') !== false) {
                    if (preg_match_all("/:(\w+)/", $k, $segment_keys)) {
                        array_shift($matches);
                        array_shift($segment_keys);
                        foreach ($segment_keys[0] as $key => $name) {
                            $this->segments[$name] = $matches[$key];
                        }
                        // register segments on Registry (container)
                        if (!empty($this->segments)) {
                            $this->registry->register($this->segments);
                        }
                    }
                }
                return $v;
            }
        }
        return false;
    }

    /**
     * Get segment value
     *
     * @param string $name
     * @return int|string|bool
     */
    public function getSegment($name)
    {
        if (isset($this->segments[$name])) {
            return $this->segments[$name];
        }
        return false;
    }    
}
