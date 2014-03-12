<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

// default PHP extension
if (!defined('PHP_FILE_EXTENSION')) {
	define('PHP_FILE_EXTENSION', '.php');
}

// controllers
if (!defined('SELF_CONTROLLER')) {
	define('SELF_CONTROLLER', 'SELF');
}

// actions
if (!defined('SELF_ACTION')) {
	define('SELF_ACTION', 'SELF');
}

// define default controller, if it's not yet defined
if (!defined('DEFAULT_CONTROLLER')) {
    define('DEFAULT_CONTROLLER', 'index');
}

// define default action/method, if it's not yet defined
if (!defined('DEFAULT_ACTION')) {
    define('DEFAULT_ACTION', 'index');
}

// define page cache checker, if it's not yet defined
if (!defined('CHECK_PAGE_CACHE')) {
    define('CHECK_PAGE_CACHE', false);
}

// define page cache expiry time, if it's not yet defined
if (!defined('PAGE_CACHE_EXPIRES')) {
    define('PAGE_CACHE_EXPIRES', 900); // 15mins
}

// presentation layer stuff
if (!defined('DEFAULT_LAYOUT')) {
	define('DEFAULT_LAYOUT', 'default');
}
