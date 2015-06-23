<?php

use Orinoco\Framework\View as View;

class json
{
    // sample JSON response
    public function index(View $view)
    {
        return $view->renderJSON(array(
                'ok' => true,
                'message' => 'Hooray! It works!'
            ));
    }
}
