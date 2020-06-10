<?php

namespace Parezban\BladeRouter;

use Parezban\BladeRouter\Exceptions\BadMethodNameException;
use Parezban\BladeRouter\Exceptions\MethodNotAllowedException;

class Router
{

    private $methods = [];

    private $route = '';

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
        $this->route = $route;

        $this->checkMethodName();
        $this->match($cb);
    }

    private function checkMethodName()
    {
        foreach ($this->methods as $method)
            if (!in_array($method, self::ALLOWED_METHODS))
                throw new BadMethodNameException(sprintf('Method not found, avalable methods are %s', implode(' - ', self::ALLOWED_METHODS)));

        return true;
    }

    private function isMethodsAllowed()
    {
        if (in_array($_SERVER['REQUEST_METHOD'], $this->methods))
            return true;

        throw new MethodNotAllowedException(sprintf('Method not allowed, avalable methods are %s', implode(' - ', $this->methods)));
    }

    private function match($cb)
    {
        if ($this->checkAddress() && $this->isMethodsAllowed()) {
            $cb();
        }

        return false;
    }

    private function checkAddress()
    {
        if ($this->route == $this->getRealAddress()) {
        }

        return true;
    }


    private function getRealAddress()
    {
        $root = $this->getRoot();
        $fullAddress =  $_SERVER['REQUEST_URI'];

        $fullAddress = ltrim($fullAddress, $root);

        return '/' . $fullAddress;
    }

    private function getRoot()
    {
        if ($this->root === null) {
            $this->root = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        }

        return $this->root;
    }
}
