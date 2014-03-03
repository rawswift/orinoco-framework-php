<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

// Turn ON all error reporting
error_reporting(E_ALL);
// ...or turn it OFF
// error_reporting(0);

// start output buffering, let View class handle the flushing of contents
ob_start();

// load framework's environment config
require '../../lib/Orinoco/Config/Environment.php';
// load framework's autoload class
require FRAMEWORK_LIB_DIR . 'AutoLoad.php';

// register framework's autoload methods
$autoload = new Orinoco\Framework\AutoLoad();
$autoload->register();

// load developer's config, if available
$app_config = APPLICATION_CONFIG_DIR . 'Application.php';
if (file_exists($app_config)) {
    require $app_config;
}

// load framework's config (default app config)
require FRAMEWORK_CONFIG_DIR . 'Application.php';

// instantiate Registry class (class/object mapper)
$registry = new Orinoco\Framework\Registry();

// instantiate and register required framework libs
$http = $registry->register(new Orinoco\Framework\Http($_SERVER));
$view = $registry->register(new Orinoco\Framework\View());

// used for checking page cache
$cache_file = md5($http->getRequestURI());

// see if we need to check page cache
if (CHECK_PAGE_CACHE && $view->isPageCacheDirWritable() && $view->isPageCached($cache_file)) {
    $cache = unserialize($view->readPageCache($cache_file));
    if (isset($cache['header'])) {
        foreach ($cache['header'] as $k => $v) {
            $http->setHeader($v);
        }
    }
    if (isset($cache['content'])) {
        $view->setContent($cache['content']);
    }
    $view->send();
} else {

    // load vendor (Composer) autoload, if it's available
    if (file_exists(APPLICATION_VENDOR_DIR . 'autoload.php')) {
        require APPLICATION_VENDOR_DIR . 'autoload.php';
    }

    // instantiate and register Route class, used for determining controller and action to use
    $route = $registry->register(new Orinoco\Framework\Route($http));

    // instantiate and register Request and Response class
    $request = $registry->register(new Orinoco\Framework\Request($http, $route));
    $response = $registry->register(new Orinoco\Framework\Response($http, $view));

    // instantiate and register Application class
    $app = $registry->register(new Orinoco\Framework\Application($request, $response));

    // load developer's route config
    $custom_routes = APPLICATION_CONFIG_DIR . 'Route.php';
    if (file_exists($custom_routes)) {
        require $custom_routes;
    }

    // load common/default route rules
    $common_routes = FRAMEWORK_CONFIG_DIR . 'Route.php';
    if (file_exists($common_routes)) {
        require $common_routes;
    }

    // parse request, the actual URI parsing process. return's false if no route is found
    if ($app->Request->Route->parseRequest()) {
        // if all goes well, instantiate Constructor class
        $constructor = new Orinoco\Framework\Constructor($app, $registry);
        // ...then dispatch the requested controller and action method
        $constructor->dispatch();
    } else {
        $http->setHeader($http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
        $view->setContent('Route Not Found');
        $view->send();
    }

}

// cleanup output buffer
ob_end_clean();
