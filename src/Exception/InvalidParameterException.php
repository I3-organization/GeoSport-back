<?php

namespace App\Exception;

use Exception;

class InvalidParameterException extends \Exception
{
    public function __construct(array $params, int $code = 400, Exception $previous = null)
    {
        $message = sprintf('Invalid parameters: %s', implode(', ', $params));
        parent::__construct($message, $code, $previous);
    }
}