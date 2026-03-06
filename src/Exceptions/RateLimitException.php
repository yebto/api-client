<?php

namespace Yebto\ApiClient\Exceptions;

class RateLimitException extends ApiException
{
    public function __construct(string $message = 'Rate limit exceeded.', ?array $responseBody = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 429, $responseBody, $previous);
    }
}
