<?php

namespace Yebto\ApiClient;

use Yebto\ApiClient\Exceptions\ApiException;
use Yebto\ApiClient\Exceptions\AuthenticationException;
use Yebto\ApiClient\Exceptions\RateLimitException;

abstract class YebtoClient
{
    protected array $config;

    /**
     * @param array $config ['key' => string, 'base_url' => string, 'curl' => array]
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->defaults(), $config);
    }

    /**
     * Default config values for this client.
     */
    abstract protected function defaults(): array;

    /**
     * The API module path (e.g. 'currency', 'bot/detect').
     */
    abstract protected function module(): string;

    /**
     * POST JSON to the API.
     *
     * @param string $action  The action segment (empty string for single-endpoint APIs)
     * @param array  $payload Request body parameters
     * @return array Decoded JSON response
     */
    protected function post(string $action, array $payload = []): array
    {
        $url = $this->buildUrl($action);
        $key = $this->config['key'] ?? null;

        if (!$key) {
            throw new AuthenticationException();
        }

        $payload['api_key'] = $key;
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);
        curl_setopt_array($ch, $this->curlOptions([
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $body,
        ]));

        $raw  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new ApiException("cURL error: {$err}");
        }

        return $this->handleResponse($raw, $code);
    }

    /**
     * POST multipart form data (for file uploads).
     *
     * @param string $action  The action segment
     * @param array  $payload Form fields (files should be CURLFile instances)
     * @return array Decoded JSON response
     */
    protected function postMultipart(string $action, array $payload = []): array
    {
        $url = $this->buildUrl($action);
        $key = $this->config['key'] ?? null;

        if (!$key) {
            throw new AuthenticationException();
        }

        $payload['api_key'] = $key;

        $ch = curl_init($url);
        curl_setopt_array($ch, $this->curlOptions([
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]));

        $raw  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new ApiException("cURL error: {$err}");
        }

        return $this->handleResponse($raw, $code);
    }

    protected function buildUrl(string $action): string
    {
        $base   = rtrim($this->config['base_url'] ?? 'https://api.yeb.to/v1', '/');
        $module = $this->module();

        return $action !== '' ? "{$base}/{$module}/{$action}" : "{$base}/{$module}";
    }

    protected function handleResponse(string $raw, int $code): array
    {
        $json = json_decode($raw, true);

        if ($code === 429) {
            $msg = is_array($json) ? ($json['error'] ?? 'Rate limit exceeded') : 'Rate limit exceeded';
            throw new RateLimitException($msg, $json);
        }

        if ($code < 200 || $code >= 300) {
            $msg = is_array($json) ? ($json['error'] ?? "API error [{$code}]") : "API error [{$code}]";
            throw new ApiException($msg, $code, $json);
        }

        if (!is_array($json)) {
            throw new ApiException('Invalid JSON response from API');
        }

        return $json;
    }

    protected function curlOptions(array $extra = []): array
    {
        $defaults = $this->config['curl'] ?? [
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_USERAGENT      => 'yebto-api-client-php',
        ];

        return $extra + $defaults;
    }
}
