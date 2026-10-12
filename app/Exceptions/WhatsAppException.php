<?php

namespace App\Exceptions;

use Exception;

class WhatsAppException extends Exception
{
    public function __construct(
        string $message = 'WhatsApp request failed',
        int $code = 0,
        public readonly array $context = [],
        public readonly bool $retryable = false,
        public readonly ?int $httpStatus = null
    ) {
        parent::__construct($message, $code);
    }

    public function isRetryable(): bool
    {
        return $this->retryable;
    }
}
