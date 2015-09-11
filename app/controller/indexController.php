<?php

use Orinoco\Framework\View as View;

class indexController
{
    public function index(View $view)
    {
    	return $view->render('index');
    }
}
