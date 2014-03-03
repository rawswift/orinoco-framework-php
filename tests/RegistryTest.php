<?php

class RegistryTest extends PHPUnit_Framework_TestCase
{
    private $mock = array(
            'USER' => 'www-data',
            'HOME' => '/var/www',
            'REQUEST_URI' => '/foo/bar',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36'
        );

    public function testSetupRegistry()
    {
        $registry = new Orinoco\Framework\Registry();
        return $registry;
    }

    /**
     * @depends testSetupRegistry
     */
    public function testRegisterClass(Orinoco\Framework\Registry $registry)
    {
        $http = new Orinoco\Framework\Http($this->mock);
        $this->assertInstanceOf('Orinoco\Framework\Http', $registry->register($http));
        return $registry;
    }

    /**
     * @depends testRegisterClass
     */
    public function testResolveClass(Orinoco\Framework\Registry $registry)
    {
        $http_obj = $registry->resolve('Orinoco\Framework\Http');
        $this->assertInstanceOf('Orinoco\Framework\Http', $http_obj);
        return $http_obj;
    }

    /**
     * @depends testResolveClass
     */
    public function testResolveHttpGetRequestURI(Orinoco\Framework\Http $http)
    {
        $this->assertEquals('/foo/bar', $http->getRequestURI());
    }

    /**
     * @depends testResolveClass
     */
    public function testResolveHttpGetValue(Orinoco\Framework\Http $http)
    {
        $this->assertEquals('www-data', $http->getValue('USER'));
        $this->assertEquals('/var/www', $http->getValue('HOME'));
        $this->assertEquals('127.0.0.1', $http->getValue('REMOTE_ADDR'));
    }
}
