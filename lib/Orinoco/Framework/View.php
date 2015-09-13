<?php
/**
 * Orinoco Framework - A lightweight PHP framework.
 *  
 * Copyright (c) 2008-2015 Ryan Yonzon, http://www.ryanyonzon.com/
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */

namespace Orinoco\Framework;

use RuntimeException;

class View
{
    // layout name
    public $layout;
    // Orinoco\Framework\Http class
    private $http;
    // Orinoco\Framework\Route class
    private $route;
    // Passed controller's variables (to be used by view template)
    private $variables;    
    // explicit view page
    private $page_view;

    /**
     * Constructor
     *
     * @param Http object $http
     * @param Route object $route
     * @return void
     */
    public function __construct(Http $http, Route $route)
    {
        $this->http = $http;
        $this->route = $route;
    }

    /**
     * Getter method
     *
     * @param Variable name $var_name
     * @return Variable value
     */
    public function __get($var_name)
    {
        if (isset($this->variables[$var_name])) {
            return $this->variables[$var_name];
        }
        return false;
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
                'controller' => $this->route->getController(),
                'action' => $this->route->getAction()
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
            $exploded = explode('#', $page_view); // use '#' as separator (we can also use '/')
            if (count($exploded) > 1) {
                if (isset($exploded[0])) {
                    $page['controller'] = $exploded[0];
                }
                if (isset($exploded[1])) {
                    $page['action'] = $exploded[1];
                }
            } else {
                $page['action'] = $page_view;
            }
        }
        $this->page_view = (object) $page;
    }

    /**
     * Render view template/page (including layout)
     *     
     * @param $page_view Explicit page view/template file
     * @param $obj_vars Variables to be passed to the layout and page template
     * @return void
     */
    public function render($page_view = null, $obj_vars = array())
    {

        if (isset($page_view)) {
            $this->setPage($page_view);
        }

        // store variables (to be passed to the layout and page template)
        // accessible via '__get' method
        $this->variables = $obj_vars;

        // check if layout is defined
        if(isset($this->layout)) {
            $layout_file = APPLICATION_LAYOUT_DIR . str_replace(PHP_FILE_EXTENSION, '', $this->layout) . PHP_FILE_EXTENSION;
            if (!file_exists($layout_file)) {
                $this->http->setHeader($this->http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                if (DEVELOPMENT) {
                    throw new RuntimeException('It seems that "' . str_replace(ROOT_DIR, '', $layout_file) . '" does not exists.');
                } else {
                    $this->renderErrorPage(500);
                }
            } else {
                require $layout_file;
            }
        } else {
            $default_layout = $this->getDefaultLayout();
            if (file_exists($default_layout)) {
                require $default_layout;
            } else {
                $this->http->setHeader($this->http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                if (DEVELOPMENT) {
                    throw new RuntimeException('It seems that "' . str_replace(ROOT_DIR, '', $default_layout) . '" does not exists.');
                } else {
                    $this->renderErrorPage(500);
                }
            }
        }
    }

    /**
     * Render error page
     *
     * @param Error code (e.g. 404, 500, etc)
     * @return void
     */
    public function renderErrorPage($error_code = null)
    {
        if (defined('ERROR_' . $error_code . '_PAGE')) {
            $error_page = constant('ERROR_' . $error_code . '_PAGE');
            $error_page_file = APPLICATION_ERROR_PAGE_DIR . str_replace(PHP_FILE_EXTENSION, '', $error_page) . PHP_FILE_EXTENSION;
            if (file_exists($error_page_file)) {
                require $error_page_file;
            } else {
                // error page not found? show this error message
                $this->http->setHeader($this->http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
                $this->setContent('500 - Internal Server Error (Unable to render ' . $error_code . ' error page)');
            }
        } else {
            // error page not found? show this error message
            $this->http->setHeader($this->http->getValue('SERVER_PROTOCOL') . ' 500 Internal Server Error', true, 500);
            $this->setContent('500 - Internal Server Error (Unable to render ' . $error_code . ' error page)');
        }
    }

    /**
     * Get action (presentation) content
     *
     * @return bool; whether or not content file exists
     */
    public function getContent()
    {
        // check if page view is specified or not        
        if (!isset($this->page_view)) {
            $content_view = APPLICATION_PAGE_DIR . $this->route->getController() . '/' . $this->route->getAction() . PHP_FILE_EXTENSION;
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
     * @param String Partial name/path $partial_name
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
    public function flush()
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

    /**
     * Construct JSON string (and also set HTTP header as 'application/json')
     *
     * @param $data Array
     * @return void
     */
    public function renderJSON($data = array())
    {
        $json = json_encode($data);
        $this->http->setHeader(array(
                'Content-Length' => strlen($json),
                'Content-type' => 'application/json;'
            ));
        $this->setContent($json);
    }

    /**
     * Redirect using header
     *
     * @param string|array $mixed
     * @param Use 'refresh' instead of 'location' $use_refresh
     * @param Time to refresh $refresh_time
     * @return void
     */
    public function redirect($mixed, $use_refresh = false, $refresh_time = 3)
    {
        $url = null;
        if (is_string($mixed)) {
            $url = trim($mixed);
        } else if (is_array($mixed)) {
            $controller = $this->route->getController();
            $action = null;
            if (isset($mixed['controller'])) {
                $controller = trim($mixed['controller']);
            }
            $url = '/' . $controller;
            if (isset($mixed['action'])) {
                $action = trim($mixed['action']);
            }
            if (isset($action)) {
                $url .= '/' . $action;
            }
            if (isset($mixed['query'])) {
                $query = '?';
                foreach ($mixed['query'] as $k => $v) {
                    $query .= $k . '=' . urlencode($v) . '&';
                }
                $query[strlen($query) - 1] = '';
                $query = trim($query);
                $url .= $query;
            }
        }
        if (!$use_refresh) {
            $this->http->setHeader('Location: ' . $url);
        } else {
            $this->http->setHeader('refresh:' . $refresh_time . ';url=' . $url);
        }

        // exit normally
        exit(0);
    }

}
