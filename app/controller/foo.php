<?php

use Orinoco\Framework\Http as Http;
use Orinoco\Framework\View as View;

class foo
{
    public function __construct()
    {
        // you can optionally set the view template/layout to use, e.g.:
        //
        //    $view->setLayout('foo'); 
        //
        // but this needs Orinoco\Framework\View as parameter, e.g.:
        // the above example will render the file app/views/layouts/foo.php
        //
        //    public function __construct(Orinoco\Framework\View $view) {...}
        // Or
        //    use Orinoco\Framework\View as View;
        //    ...
        //    public function __construct(View $view) {...}
        //
        // else the framework will find and render a default template (app/views/layouts/application.php)
    }

    // this will be the function that'll be executed when you go to the URI: /foo
    public function index()
    {
        // nothing to do here.
        // see view/page/foo/index.php (view template)
    }

    // this will be the function that'll be executed when you go to the URI: /foo/bar
    public function bar()
    {
        // pass variable on view template
        $this->title = 'Lorem Ipsum';
        $this->content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nec libero dui. Phasellus et euismod nibh. Integer vitae leo odio. Proin egestas enim quis imperdiet malesuada. Nunc et sagittis justo. Maecenas eu nunc sed libero aliquet lacinia vel a diam. Aliquam non tempus purus. Nunc et ipsum id urna condimentum molestie. Sed sollicitudin dictum elit in commodo. Phasellus feugiat ipsum pharetra, pellentesque justo congue, blandit leo.';
        // see view/page/foo/bar.php (view template)
    }

    // sample JSON response, using Http and View classes
    public function json(Http $http, View $view)
    {
        $json = json_encode(array(
                'ok' => true,
                'message' => 'Hello World!'
            ));
        $http->setHeader(array(
                'Content-Length' => strlen($json),
                'Content-type' => 'application/json;'
            ));
        // skip HTML views (templates)
        $view->disable();
        // ...and just render the JSON object
        $view->setContent($json);
    }
}
