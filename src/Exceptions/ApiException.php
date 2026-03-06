<?php

namespace Yebto\ApiClient\Exceptions;

use RuntimeException;

class ApiException extends RuntimeException
{
    protected int $httpCode;
    protected ?array $responseBody;

    public function __construct(string $message, int $httpCode = 0, ?array $responseBody = null, ?\Throwable $previous = null)
    {
        $this->httpCode = $httpCode;
        $this->responseBody = $responseBody;
        parent::__construct($message, $httpCode, $previous);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }
}
