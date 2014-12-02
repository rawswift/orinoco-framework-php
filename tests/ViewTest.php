<?php

class ViewTest extends PHPUnit_Framework_TestCase
{
    public function testSetupView()
    {
        $view = new Orinoco\Framework\View();
        return $view;
    }

    /**
     * @depends testSetupView
     */
    public function testSetLayout(Orinoco\Framework\View $view)
    {
        $view->setLayout('fancy-template');
        $this->assertEquals('fancy-template', $view->layout);
    }

    /**
     * @depends testSetupView
     */
    public function testDisableView(Orinoco\Framework\View $view)
    {
        $this->assertEquals(true, $view->isViewEnabled()); // default: True
        $view->disable();
        $this->assertEquals(false, $view->isViewEnabled());
    }    
}
