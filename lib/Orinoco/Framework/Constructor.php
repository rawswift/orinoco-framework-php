<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

class Constructor
{
    // controller name
    protected $controller;
    // action name
    protected $action;
    // Application instance
    protected $app;
    // Registry instance (class map)
    protected $registry;

    /**
     * Constructor, setup properties
     *
     * @param Application object $app
     * @param Registry object $registry
     * @return void
     */
    public function __construct(Application $app, Registry $registry)
    {
        $this->app = $app;
        $this->registry = $registry;

        $this->controller = $app->Request->Route->getController();
        $this->action = $app->Request->Route->getAction();
    }

    /**
     * Dispatch, instantiate controller class, execute action method and then prepare/render view
     *
     * @return void
     */
    public function dispatch()
    {
        $controller = $this->controller;
        $action = $this->action;
        if (defined('APPLICATION_NAMESPACE')) {
            $controller = str_replace('\\', '', APPLICATION_NAMESPACE) . '\\' . $controller;
        }
        if (class_exists($controller)) {

            // check if __construct method needs dependencies
            $this->registry->reflectionLoad($controller);
            $dependencies = $this->registry->reflectionGetMethodDependencies('__construct');

            // instantiate the user's controller using reflector
            $obj = $this->registry->reflectionCreateInstance($dependencies);

            // check if object method exists
            if (method_exists($obj, $action)) {

                // check if action method needs dependency
                $dependencies = $this->registry->reflectionGetMethodDependencies($action);
                // run/call the controller's action method
                call_user_func_array(array($obj, $action), $dependencies);

                // store controller/object's variables
                // we'll make them visible on the presentation layer (templates)
                $obj_variables = array();
                foreach($obj as $k => $v) {
                    $obj_variables[$k] = $v;
                }

                // check if view is enabled
                if ($this->app->Response->View->viewEnabled()) {
                    // pass reference of Orinoco\Framework\Application object and controller object's variables
                    // ...and render layout template
                    $this->app->Response->View->renderLayout($this->app, $obj_variables);
                }

            } else {
                // no action method found!
                $this->app->Response->Http->setHeader($this->app->Request->Http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
                $this->app->Response->View->setContent('Cannot find method "' . $action . '" on controller class "' . $controller . '"');
            }
        } else {
            // no controller class found!
            $this->app->Response->Http->setHeader($this->app->Request->Http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
            $this->app->Response->View->setContent('Cannot find controller class "' . $controller . '"');
        }
        // check if we need to cache output/page and response header
        $cache_file = md5($this->app->Response->Http->getRequestURI());
        if ($this->app->Response->View->cachePage() && $this->app->Response->View->isPageCacheDirWritable() && !$this->app->Response->View->isPageCached($cache_file)) {
            // serialize before storing
            $cache = array(
                    'header' => headers_list(),
                    'content' => ob_get_contents()
                );
            $this->app->Response->View->writePageCache($cache_file, serialize($cache));
        }
        // flush the response
        $this->app->Response->View->send();
    }
}
