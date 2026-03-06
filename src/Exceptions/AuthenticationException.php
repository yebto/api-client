<?php

namespace Yebto\ApiClient\Exceptions;

class AuthenticationException extends ApiException
{
    public function __construct(string $message = 'Missing or invalid API key.', ?\Throwable $previous = null)
    {
        parent::__construct($message, 401, null, $previous);
    }
}
