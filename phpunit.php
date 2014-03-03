<?php
/**
 * PHPUnit bootstrap script
 * $ phpunit --bootstrap phpunit.php tests/
 */

define('ROOT_DIR', './');
// load framework's environment config
require_once 'lib/Orinoco/Config/Environment.php';
// load framework's config (default app config)
require_once FRAMEWORK_CONFIG_DIR . 'Application.php';
// load framework's autoload class
require_once FRAMEWORK_LIB_DIR . 'AutoLoad.php';

// register framework's autoload methods
$autoload = new Orinoco\Framework\AutoLoad();
$autoload->register();
