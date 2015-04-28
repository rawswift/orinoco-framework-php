<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

// root directory
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', '../../');
}

// framework related stuff
define('FRAMEWORK_BASE_DIR', ROOT_DIR . 'lib/');
define('FRAMEWORK_VENDOR_DIR', FRAMEWORK_BASE_DIR . 'Orinoco/');
define('FRAMEWORK_LIB_DIR', FRAMEWORK_VENDOR_DIR . 'Framework/');
define('FRAMEWORK_CONFIG_DIR', FRAMEWORK_VENDOR_DIR . 'Config/');

// application related stuff
define('APPLICATION_BASE_DIR', ROOT_DIR . 'app/');
define('APPLICATION_CONFIG_DIR', APPLICATION_BASE_DIR . 'config/');
define('APPLICATION_CONTROLLER_DIR', APPLICATION_BASE_DIR . 'controller/');
define('APPLICATION_VENDOR_DIR', APPLICATION_BASE_DIR . 'vendor/');
define('APPLICATION_CLASS_DIR', APPLICATION_BASE_DIR . 'class/');
define('APPLICATION_VIEW_DIR', APPLICATION_BASE_DIR . 'view/');
define('APPLICATION_LAYOUT_DIR', APPLICATION_VIEW_DIR . 'layout/');
define('APPLICATION_PAGE_DIR', APPLICATION_VIEW_DIR . 'page/');
define('APPLICATION_PARTIAL_DIR', APPLICATION_VIEW_DIR . 'partial/');
define('APPLICATION_ERROR_PAGE_DIR', APPLICATION_VIEW_DIR . 'error/');
