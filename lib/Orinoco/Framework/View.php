<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2014 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

use RuntimeException;

class View
{
    // layout name
    public $layout;
    // whether or not to render view/template (HTML layout and HTML contents)
    // default is true (render view/template)
    public $view_enabled = true;
    // whether or not to cache output/page and response header
    // default is false (do not cache)
    public $cache_page = false;
    // Orinoco\Framework\Application class
    private $app;
    // Passed controller's variables (to be used by view template)
    private $variables;    
    // explicit view page
    private $page_view;

    /**
     * Constructor
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Getter method
     *
     * @param Variable name $var_name
     * @return Variable value
     */
    public function __get($var_name)
    {
        return $this->variables[$var_name];
    }    
    
    /**
     * Disable view/template rendering
     *
     * @return void
     */
    public function disable()
    {
        $this->view_enabled = false;
    }

    /**
     * Get view flag
     *
     * @return bool; whether or not view is enabled
     */
    public function isViewEnabled()
    {
        return $this->view_enabled;
    }

    /**
     * Set HTML layout
     *
     * @param Layout name     
     * @return void
     */
    public function setLayout($layout_name)
    {
        $this->layout = $layout_name;
    }

    /**
     * Set page/view template to use
     *     
     * @param Page/view name Array or String
     * @return void
     */
    public function setPage($page_view)
    {
        // initialize default page view/template
        $page = array(
                'controller' => DEFAULT_CONTROLLER,
                'action' => DEFAULT_ACTION
            );
        // check if passed parameter is an array
        if (is_array($page_view)) {
            if (isset($page_view['controller'])) {
                $page['controller'] = $page_view['controller'];
            }
            if (isset($page_view['action'])) {
                $page['action'] = $page_view['action'];
            }
        // string
        } else if (is_string($page_view)) {
            $exploded = explode('/', $page_view);
            if (isset($exploded[0])) {
                $page['controller'] = $exploded[0];
            }
            if (isset($exploded[1])) {
                $page['action'] = $exploded[1];
            }
        }
        $this->page_view = (object) $page;
    }

    /**
     * Render presentation layout
     *
     * @param Applicaton object $app
     * @param Controller's variables $obj_vars (Array)
     * @return void
     */
    public function renderLayout(Application $app, $obj_vars)
    {
        $this->app = $app;
        $this->variables = $obj_vars;

        // check if layout is defined
        if(isset($this->layout)) {
            $layout_file = APPLICATION_LAYOUT_DIR . str_replace(PHP_FILE_EXTENSION, '', $this->layout) . PHP_FILE_EXTENSION;
            if (!file_exists($layout_file)) {
                $app->Response->Http->setHeader($app->Request->Http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                if (DEVELOPMENT) {
                    throw new RuntimeException('It seems that "' . str_replace(ROOT_DIR, '', $layout_file) . '" does not exists.');
                } else {
                    $app->Response->View->renderErrorPage($app, 500);
                }
                $app->Response->View->send();

            } else {
                require $layout_file;
            }
        } else {
            $default_layout = $app->Response->View->getDefaultLayout();
            if (file_exists($default_layout)) {
                require $default_layout;
            } else {
                $app->Response->Http->setHeader($app->Request->Http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                if (DEVELOPMENT) {
                    throw new RuntimeException('It seems that "' . str_replace(ROOT_DIR, '', $default_layout) . '" does not exists.');
                } else {
                    $app->Response->View->renderErrorPage($app, 500);
                }
                $app->Response->View->send();
            }
        }
    }

    /**
     * Render error page
     *
     * @param Applicaton object $app
     * @param Error code (e.g. 404, 500, etc)
     * @return void
     */
    public function renderErrorPage(Application $app, $error_code = null)
    {
        if (defined('ERROR_' . $error_code . '_PAGE')) {
            $error_page = constant('ERROR_' . $error_code . '_PAGE');
            $error_page_file = APPLICATION_ERROR_PAGE_DIR . str_replace(PHP_FILE_EXTENSION, '', $error_page) . PHP_FILE_EXTENSION;
            if (file_exists($error_page_file)) {
                require $error_page_file;
            } else {
                // error page not found? show this error message
                $app->Response->Http->setHeader($app->Request->Http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                $app->Response->View->setContent('500 - Internal Server Error (Unable to render ' . $error_code . ' error page)');
                $app->Response->View->send();
            }
        } else {
            // error page not found? show this error message
            $app->Response->Http->setHeader($app->Request->Http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
            $app->Response->View->setContent('500 - Internal Server Error (Unable to render ' . $error_code . ' error page)');
            $app->Response->View->send();
        }
    }

    /**
     * Get action (presentation) content
     *
     * @return bool; whether or not content file exists
     */
    public function getContent()
    {
        $app = $this->app;
        
        // inherit variables
        /*foreach($this->variables as $k => $v) {
            $this->$k = $$k = $v;
        }*/

        // check if page view is specified or not        
        if (!isset($this->page_view)) {
            $content_view = APPLICATION_PAGE_DIR . $app->Request->Route->getController() . '/' . $app->Request->Route->getAction() . PHP_FILE_EXTENSION;
        } else {
            $content_view = APPLICATION_PAGE_DIR . $this->page_view->controller . '/' . $this->page_view->action . PHP_FILE_EXTENSION;
        }

        /**
         * @todo Should we render an error page, saying something like "the page template aren't found"?
         */
        if(!file_exists($content_view)) {
            // No verbose
            return false;
        }
        require $content_view;
    }

    /**
     * Get partial (presentation) content
     *
     * @return bool; whether or not partial file exists
     */
    public function getPartial($partial_name)
    {
        $partial_view = APPLICATION_PARTIAL_DIR . $partial_name . PHP_FILE_EXTENSION;
        if(!file_exists($partial_view)) {
            // No verbose
            return false;
        }
        require $partial_view;
    }

    /**
     * Clear output buffer content
     *
     * @return void
     */
    public function clearContent()
    {
        ob_clean();
    }

    /**
     * Print out passed content
     *
     * @return void
     */
    public function setContent($content = null)
    {
        print($content);
    }

    /**
     * Flush output buffer content
     *
     * @return void
     */
    public function send()
    {
        ob_flush();
    }

    /**
     * Return the default layout path
     *
     * @return string
     */
    private function getDefaultLayout()
    {
        return APPLICATION_LAYOUT_DIR . DEFAULT_LAYOUT . PHP_FILE_EXTENSION;
    }

}
