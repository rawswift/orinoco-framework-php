<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Application
{
    public $Request;
    public $Response;
    public $Registry;
    
    /**
     * Constructor, setup properties
     *
     * @param Request object $request
     * @param Response object $response
     * @param Registry object $registry
     * @return void
     */
    public function __construct(Request $request, Response $response, Registry $registry)
    {
        $this->Request = $request;
        $this->Response = $response;
        $this->Registry = $registry;
    }

    /**
     * Redirect using header
     *
     * @param string|array $mixed
     * @param Use 'refresh' instead of 'location' $use_refresh
     * @param Time to refresh $refresh_time
     * @return void
     */
    public function redirect($mixed, $use_refresh = false, $refresh_time = 3)
    {
        $url = null;
        if (is_string($mixed)) {
            $url = trim($mixed);
        } else if (is_array($mixed)) {
            $controller = $this->Request->Route->getController();
            $action = null;
            if (isset($mixed['controller'])) {
                $controller = trim($mixed['controller']);
            }
            $url = '/' . $controller;
            if (isset($mixed['action'])) {
                $action = trim($mixed['action']);
            }
            if (isset($action)) {
                $url .= '/' . $action;
            }
            if (isset($mixed['query'])) {
                $query = '?';
                foreach ($mixed['query'] as $k => $v) {
                    $query .= $k . '=' . urlencode($v) . '&';
                }
                $query[strlen($query) - 1] = '';
                $query = trim($query);
                $url .= $query;
            }
        }
        if (!$use_refresh) {
            $this->Response->Http->setHeader('Location: ' . $url);
        } else {
            $this->Response->Http->setHeader('refresh:' . $refresh_time . ';url=' . $url);
        }
    }
}
