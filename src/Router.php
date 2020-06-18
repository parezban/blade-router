<?php

namespace Parezban\BladeRouter;

use Parezban\BladeRouter\Exceptions\BadMethodNameException;
use Parezban\BladeRouter\Exceptions\MethodNotAllowedException;

class Router
{

    private $methods = [];

    private $route = '';

    private $params = [];

    private const ALLOWED_METHODS = [
        'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH', 'HEAD'
    ];
    const REG_FOR_KNOWN_TYPES_PARAMS   = '/(\{[a-z\_]+[a-z\_0-9]*\})\(/';
    const REG_FOR_UNKNOWN_TYPES_PARAMS = '/(\{[a-z\_]+[a-z\_0-9]*\})(\/|$)/';

    private $root = null;


    public function add(array $methods, string $route, $cb)
    {
        $this->methods = $methods;
        $this->route = $route;
        $this->params = [];

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
            $params = $this->getUrlParamsValue();
      
            $cb();
        }

        return false;
    }

    private function getUrlParamsValue()
    {

        $routeArray = explode('/', $this->route);
        $currentAddress = explode('/', $this->getRealAddress());

        $paramsValues = [];
        foreach ($this->params as  $param) {
            foreach ($routeArray as $indexRouteArray => $routePattern) {
                if (strpos($routePattern, $param) !== false) {
                    $paramsValues[$indexRouteArray] = $currentAddress[$indexRouteArray];
                    break;
                }
            }
        }
        rsort($paramsValues);

        return $paramsValues;
    }

    private function purifyAndCollectUrlParams()
    {
        $cleanRoute = $this->route;

        preg_match_all(self::REG_FOR_KNOWN_TYPES_PARAMS, $this->route, $knownTypesParams);
        preg_match_all(self::REG_FOR_UNKNOWN_TYPES_PARAMS, $this->route, $unknownTypesParams);
        if (isset($knownTypesParams[1]))
            foreach ($knownTypesParams[1] as $param) {
                $cleanRoute = str_replace($param, '', $cleanRoute);
                $this->params[] = $param;
            }

        if (isset($unknownTypesParams[1]))
            foreach ($unknownTypesParams[1] as $param) {
                $cleanRoute = str_replace($param, '(.*)', $cleanRoute);
                $this->params[] = $param;
            }

        return $cleanRoute;
    }

    private function checkAddress()
    {

        $route = $this->purifyAndCollectUrlParams();

        $escapedUrl = str_replace('/', '\/', $route);

        if (preg_match('/^' . $escapedUrl . '$/', $this->getRealAddress()))
            return true;

        return false;
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
