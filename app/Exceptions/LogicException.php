<?php

namespace App\Exceptions;

use Exception;

class LogicException extends Exception
{
    /**
     * Create a new class instance.
     */
    public function __construct($message = "An error occurred", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
