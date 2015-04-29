<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

use RuntimeException;

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
     * Run application, instantiate controller class and execute action method
     *
     * @return void
     */
    public function run()
    {
        $controller = $this->Request->Route->getController();
        $action = $this->Request->Route->getAction();

        if (defined('APPLICATION_NAMESPACE')) {
            $controller = str_replace('\\', '', APPLICATION_NAMESPACE) . '\\' . $controller;
        }
        if (class_exists($controller)) {

            // load reflection of controller/class
            $this->Registry->reflectionLoad($controller);

            // check if "__construct" method exists
            // if Yes, then get dependencies/parameters info
            $dependencies = array();
            if (method_exists($controller, '__construct')) {
                $dependencies = $this->Registry->reflectionGetMethodDependencies('__construct');
            }

            // instantiate the user's controller using reflector
            $obj = $this->Registry->reflectionCreateInstance($dependencies);

            // check if object method exists
            if (method_exists($obj, $action)) {

                // check if action method needs dependency
                $dependencies = $this->Registry->reflectionGetMethodDependencies($action);
                // run/call the controller's action method
                return call_user_func_array(array($obj, $action), $dependencies);

            } else {
                // no action method found!
                $this->Response->Http->setHeader($this->Request->Http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
                if (DEVELOPMENT) {
                    throw new RuntimeException('Cannot find method "' . $action . '" in controller class "' . $controller . '"');
                } else {
                    return $this->Response->View->renderErrorPage(404);
                }

            }
        } else {
            // no controller class found!
            $this->Response->Http->setHeader($this->Request->Http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
            if (DEVELOPMENT) {
                throw new RuntimeException('Cannot find controller class "' . $controller . '"');
            } else {
                return $this->Response->View->renderErrorPage(404);
            }            
        }
        // flush the response
        // $this->Response->View->send();
    }

}
