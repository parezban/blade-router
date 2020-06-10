<?php

namespace Parezban\BladeRouter\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, 405, $previous);
    }
}
