<?php

class RouteTest extends PHPUnit_Framework_TestCase
{
    private $mock = array(
            'USER' => 'www-data',
            'HOME' => '/var/www',
            'REQUEST_URI' => '/foo/123',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36'
        );

    public function testSetupRoute()
    {
        $http = new Orinoco\Framework\Http($this->mock);   
        $registry = new Orinoco\Framework\Registry();    
        $route = new Orinoco\Framework\Route($http, $registry);
        $route->setRoute('/foo/:id', array(
            'controller' => 'foo',
            'action' => 'bar',
            'filters' => array(
                'id' => '(\d+)'
                )
            ));
        return $route;
    }

    /**
     * @depends testSetupRoute
     */
    public function testRouteTable(Orinoco\Framework\Route $route)
    {
        $route_table = $route->getRouteTable();
        $this->assertEquals(array(
                '/foo/:id' => array(
                                'controller' => 'foo',
                                'action' => 'bar',
                                'filters' => array(
                                        'id' => '(\d+)'
                                    )
                            )
            ), $route_table);
        return $route;
    }

    /**
     * @depends testSetupRoute
     */
    public function testParseRequest(Orinoco\Framework\Route $route)
    {
        $this->assertEquals(true, $route->parseRequest());
        return $route;
    }

    /**
     * @depends testParseRequest
     */
    public function testGetControllerName(Orinoco\Framework\Route $route)
    {
        $this->assertEquals('foo', $route->getController());
    }

    /**
     * @depends testParseRequest
     */
    public function testGetActionName(Orinoco\Framework\Route $route)
    {
        $this->assertEquals('bar', $route->getAction());
    }

    /**
     * @depends testParseRequest
     */
    public function testPathNotDefined(Orinoco\Framework\Route $route)
    {
        $this->assertEquals(false, $route->isPathDefined());
    }

    /**
     * @depends testParseRequest
     */
    public function testGetSegment(Orinoco\Framework\Route $route)
    {
        $this->assertEquals(123, $route->getSegment('id'));
    }    
}
