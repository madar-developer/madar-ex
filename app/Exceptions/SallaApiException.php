<?php

namespace App\Exceptions;

use Exception;

class SallaApiException extends Exception
{
    public function __construct(
        public readonly array $responseData = [],
        string $message = 'Salla API request failed',
        int $code = 0
    ) {
        parent::__construct($message, $code);
    }
}