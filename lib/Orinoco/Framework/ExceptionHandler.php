<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

use ErrorException;

class ExceptionHandler
{
    private $http;
    private $view;

    /**
     * Constructor, setup properties/dependencies
     *
     * @param Http object $http
     * @param View object $view
     * @return void
     */
    public function __construct(Http $http, View $view)
    {
        $this->http = $http;
        $this->view = $view;
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
        $this->http->setHeader($this->http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', false, 500);
        if (DEVELOPMENT) {
            $this->view->setContent('EXCEPTION: ' . $exception->getMessage() . '<br />BACKTRACE: <pre><code>' . $exception->getTraceAsString() . '</code></pre><br />');
        } else {
            $this->view->renderErrorPage($this->app, 500);
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
