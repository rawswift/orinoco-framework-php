<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

use ErrorException;
use Orinoco\Framework\Application as Application;

class ExceptionHandler
{
    protected $app;

    /**
     * Constructor, setup properties/dependencies
     *
     * @param Application object $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register the handlers
     *
     * @param void
     * @return void
     */
    public function register()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        // we might need to register a shutdown handler in the future
        // register_shutdown_function(array($this, 'shutdownHandler'));
    }

    /**
     * Error Handler
     *
     * @param Integer $errno
     * @param String $errstr
     * @param String $errfile
     * @param Integer $errline
     * @param Array $errcontext
     * @return void/bool
     */
    public function errorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array())
    {
        if (!(error_reporting() & $errno)) {
            return;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        // Don't execute PHP internal error handler
        return true;
    }

    /**
     * Exception Handler
     *
     * @param Exception $exception
     * @return void
     */
    public function exceptionHandler($exception)
    {
        $this->app->Response->Http->setHeader($this->app->Response->Http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', false, 500);
        if (DEBUG) {
            $this->app->Response->View->setContent('EXCEPTION: ' . $exception->getMessage() . '<br />BACKTRACE: <pre><code>' . $exception->getTraceAsString() . '</code></pre><br />');
        } else {
            $this->app->Response->View->renderErrorPage($this->app, 500);
        }
    }

    /**
     * Shutdown Handler
     *
     * @param void
     * @return void
     */
    public function shutdownHandler()
    {
        // last chance to get errors
        $error = error_get_last();
    }    
}
