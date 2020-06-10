<?php

namespace Parezban\BladeRouter;

use Parezban\BladeRouter\Exceptions\BadMethodName;

class Router
{

    private $methods = [];

    private const ALLOWED_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD'
    ];

    private $root = null;

    public function __construct($root = null)
    {
        $this->root = $root;
    }

    public function add(array $methods, string $route, $cb)
    {
        $this->methods = $methods;

        $this->checkMethod();
        $this->match($route, $cb);
    }

    private function checkMethod()
    {
        foreach ($this->methods as $method)
            if (!in_array($method, self::ALLOWED_METHODS))
                throw new BadMethodName();

        return true;
    }

    private function match($route, $cb)
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        if ($route) {
            $cb();
        }

        return false;
    }

    private function getRoot()
    {
        if ($this->root === null) {
            $this->root = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        }

        return $this->root;
    }
}
