<?php

use PHPUnit\Framework\TestCase;

class Routertest extends TestCase
{
    public function testClass()
    {
        $this->assertInstanceOf('\Parezban\BladeRouter\Router', new Parezban\BladeRouter\Router());
    }

    public function testMixRoute()
    {
        $router = new \Parezban\BladeRouter\Router();
        ob_start();


        $_SERVER['REQUEST_URI'] = '/1/bladeRouter/42';

        $router->add(['GET'], '/1/{index}/(\d+)', function ($index)  {
            echo $index;
        });

        $this->assertEquals('bladeRouter', ob_get_contents());
        ob_clean();
        ob_end_clean();

    }

    public function testParamRoute()
    {
        $router = new \Parezban\BladeRouter\Router();
        ob_start();


        $_SERVER['REQUEST_URI'] = '/1/bladeRouter';

        $router->add(['GET'], '/1/{index}', function ($index)  {
            echo $index;
        });

        $this->assertEquals('bladeRouter', ob_get_contents());
        ob_clean();
        ob_end_clean();

    }

    public function testDynamicRoute()
    {
        $router = new \Parezban\BladeRouter\Router();
        ob_start();


        $_SERVER['REQUEST_URI'] = '/1/mixxx';

        $router->add(['GET'], '/(\d+)/mixxx', function ()  {
            echo '1';
        });

        $this->assertEquals('1', ob_get_contents());
        ob_clean();
        ob_end_clean();

    }

    public function testStaticRoute()
    {
        $router = new \Parezban\BladeRouter\Router();
        ob_start();


        $_SERVER['REQUEST_URI'] = '/xxx/mixxx';

        $router->add(['GET'], '/xxx/mixxx', function ()  {
            echo 'xxx';
        });

        $this->assertEquals('xxx', ob_get_contents());
        ob_clean();
        ob_end_clean();

    }
    public function testMethods()
    {
        $router = new \Parezban\BladeRouter\Router();
        $_SERVER['REQUEST_URI'] = '/';


        ob_start();

        // Test GET
        // Create Router
        $router->add(['GET'], '/', function () {
            echo 'GET';
        });

        $this->assertEquals('GET', ob_get_contents());
        ob_clean();

        // Test POST
        $_SERVER['REQUEST_METHOD'] = 'POST';
        // Create Router
        $router->add(['POST'], '/', function () {
            echo 'POST';
        });

        $this->assertEquals('POST', ob_get_contents());
        ob_clean();


        // Test PATCH
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        // Create Router
        $router->add(['PATCH'], '/', function () {
            echo 'PATCH';
        });

        $this->assertEquals('PATCH', ob_get_contents());
        ob_clean();


        // Test DELETE
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        // Create Router
        $router->add(['DELETE'], '/', function () {
            echo 'DELETE';
        });

        $this->assertEquals('DELETE', ob_get_contents());
        ob_clean();


        // Test PUT
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        // Create Router
        $router->add(['PUT'], '/', function () {
            echo 'PUT';
        });

        $this->assertEquals('PUT', ob_get_contents());
        ob_clean();

        // Test OPTIONS
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        // Create Router
        $router->add(['OPTIONS'], '/', function () {
            echo 'OPTIONS';
        });

        $this->assertEquals('OPTIONS', ob_get_contents());
        ob_clean();

        // Test HEAD
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        // Create Router
        $router->add(['HEAD'], '/', function () {
            echo 'HEAD';
        });

        $this->assertEquals('HEAD', ob_get_contents());
        ob_clean();

        ob_end_clean();
    }
}
