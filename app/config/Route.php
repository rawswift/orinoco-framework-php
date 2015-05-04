<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Basic samples
 */

// basic custom routes
// $route->setRoute("/foo", array("controller" => "foo", "action" => "index"));
// $route->setRoute("/foo/bar", array("controller" => "foo", "action" => "bar"));

// regular expression e.g. http://mydomain.com/foo/bar
// $route->setRoute('(^\/+[a-zA-Z]+\/+[a-zA-Z]([^/]+)/?$)', array('controller' => 'foo', 'action' => 'bar'));

// regular expression and specific controller class path e.g. /foo/test
// $route->setRoute("(^\/+[a-zA-Z]+\/test+$)", array("controller" => "test", "action" => "index", "path" => "/path/to/test.php"));

// override default index (home page) route
// $route->setRoute("/", array("controller" => "index", "action" => "index", "path" => "/path/to/custom/home.php"));

/**
 * Advance samples
 */

/**
 * Use segment name and filters
 *
 *   $route->setRoute('/foo/:id', array(
 *           'controller' => 'foo',
 *           'action' => 'bar',
 *           'filters' => array(
 *                   'id' => '(\d+)' // "id" as digits only
 *               )
 *       ));
 *
 *   $route->setRoute('/foo/:type/:id', array(
 *           'controller' => 'foo',
 *           'action' => 'type',
 *           'filters' => array(
 *                   'type' => '(\w+)', // "type" as letters and digits only
 *                   'id' => '(\d+)' // "id" as digits only
 *               )
 *       )); 
*/
