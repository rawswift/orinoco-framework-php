<?php

use Orinoco\Framework\View as View;

class index
{
    public function index(View $view)
    {
    	return $view->render('index');
    }
}
