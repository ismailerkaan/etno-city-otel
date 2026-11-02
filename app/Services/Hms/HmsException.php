<?php

namespace App\Services\Hms;

use RuntimeException;

class HmsException extends RuntimeException
{
    public function __construct(string $message, public readonly string|int|null $errorCode = null)
    {
        parent::__construct($message);
    }
}
