<?php

class RequestTest extends PHPUnit_Framework_TestCase
{
    private $mock = array(
            'USER' => 'www-data',
            'HOME' => '/var/www',
            'REQUEST_URI' => '/foo/bar',
            'REQUEST_METHOD' => 'GET',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36'
        );

    public function testSetupRequest()
    {
        $http = new Orinoco\Framework\Http($this->mock);
        $registry = new Orinoco\Framework\Registry();
        $route = new Orinoco\Framework\Route($http, $registry);
        $request = new Orinoco\Framework\Request($http, $route);
        return $request;
    }

    /**
     * @depends testSetupRequest
     */
    public function testPassedInstances(Orinoco\Framework\Request $request)
    {
        $this->assertInstanceOf('Orinoco\Framework\Http', $request->Http);
        $this->assertInstanceOf('Orinoco\Framework\Route', $request->Route);
    }
}
