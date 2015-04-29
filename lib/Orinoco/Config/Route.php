<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

/**
 * Default routes
 *
 * see also: /lib/Orinoco/Config/Application.php, for default controller and action names
 */

// [/controller/action/] or [/controller/action] e.g. /foo/bar
$route->setRoute('(^\/+[a-zA-Z]+\/+[a-zA-Z]([^/]+)/?$)', array('controller' => SELF_CONTROLLER, 'action' => SELF_ACTION));

// [/controller/] or [/controller] e.g. /foo
$route->setRoute('(^\/+[a-zA-Z]([^/]+)/?$)', array('controller' => SELF_CONTROLLER, 'action' => DEFAULT_ACTION));

// index/root (e.g. http://www.domain.tld/)
$route->setRoute('(^\/$)', array('controller' => DEFAULT_CONTROLLER, 'action' => DEFAULT_ACTION));
