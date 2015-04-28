<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

// by default, turn ON all error reporting
error_reporting(E_ALL);

// start output buffering, let View class handle the flushing of contents
ob_start();

// load framework's environment config
require '../../lib/Orinoco/Config/Environment.php';
// load framework's autoload class
require FRAMEWORK_LIB_DIR . 'AutoLoad.php';

// register framework's autoload methods
$autoload = new Orinoco\Framework\AutoLoad();
$autoload->register();

// load vendor (Composer) autoload, if it's available
if (file_exists(APPLICATION_VENDOR_DIR . 'autoload.php')) {
    require APPLICATION_VENDOR_DIR . 'autoload.php';
}

// instantiate Registry class (class/object mapper)
$registry = new Orinoco\Framework\Registry();

// load developer's config (if available)
$app_config = APPLICATION_CONFIG_DIR . 'Application.php';
if (file_exists($app_config)) {
    require $app_config;
}

// load framework's config (default app config)
$app_default_config = FRAMEWORK_CONFIG_DIR . 'Application.php';
if (file_exists($app_default_config)) {
    require $app_default_config;
}

// check if we need to turn OFF errors
if (!DEVELOPMENT) {
    error_reporting(0);
}

// check if we need to start session
if(SESSION && (session_id() === "")) {
    session_start();
}

// instantiate and register required framework libs
$http = $registry->register(new Orinoco\Framework\Http($_SERVER));
$view = $registry->register(new Orinoco\Framework\View());

// instantiate and register Route class, used for determining controller and action to use
$route = $registry->register(new Orinoco\Framework\Route($http, $registry));

// instantiate and register Request and Response class
$request = $registry->register(new Orinoco\Framework\Request($http, $route));
$response = $registry->register(new Orinoco\Framework\Response($http, $view));

// instantiate and register Application class
$app = $registry->register(new Orinoco\Framework\Application($request, $response, $registry));

// register Exception handler
$exception = $registry->register(new Orinoco\Framework\ExceptionHandler($app));
$exception->register();

// load developer's registry config
$custom_registry = APPLICATION_CONFIG_DIR . 'Registry.php';
if (file_exists($custom_registry)) {
    require $custom_registry;
}

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
    $constructor = new Orinoco\Framework\Constructor($app);
    // ...then dispatch the requested controller and action method
    $constructor->dispatch();
} else {
    $http->setHeader($http->getValue('SERVER_PROTOCOL') . ' 404 Not Found', true, 404);
    if (DEVELOPMENT) {
        throw new Exception('Route Not Found');
    } else {
        $view->renderErrorPage($app, 404);
    }
    $view->send();
}

// cleanup output buffer
ob_end_clean();
