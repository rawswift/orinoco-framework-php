<?php

class HttpTest extends PHPUnit_Framework_TestCase
{
    private $mock = array(
            'USER' => 'www-data',
            'HOME' => '/var/www',
            'REQUEST_URI' => '/foo/bar',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36'
        );

    public function testSetupHttp()
    {
        $http = new Orinoco\Framework\Http($this->mock);
        return $http;
    }

    /**
     * @depends testSetupHttp
     */
    public function testGetRequestURI(Orinoco\Framework\Http $http)
    {
        $this->assertEquals('/foo/bar', $http->getRequestURI());
    }

    /**
     * @depends testSetupHttp
     */
    public function testGetServerInfo(Orinoco\Framework\Http $http)
    {
        $this->assertEquals(array(
            'USER' => 'www-data',
            'HOME' => '/var/www',
            'REQUEST_URI' => '/foo/bar',
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36'
        ), $http->getServerInfo());
    }

    /**
     * @depends testSetupHttp
     */
    public function testGetValue(Orinoco\Framework\Http $http)
    {
        $this->assertEquals('/foo/bar', $http->getValue('REQUEST_URI'));
        $this->assertEquals('127.0.0.1', $http->getValue('REMOTE_ADDR'));
    }    
}
