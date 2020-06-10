<?php

namespace Parezban\BladeRouter\Exceptions;

use Exception;

class BadMethodNameException extends Exception
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
