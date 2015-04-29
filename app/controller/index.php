<?php

use Orinoco\Framework\View as View;

class index
{
    public function __construct()
    {
        // code here are always executed
    }

    public function index(View $view)
    {
    	return $view->render('index');
    }
}
