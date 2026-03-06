# YEB API Client

Base PHP client for all [YEB](https://yeb.to) API SDKs. This package provides the shared HTTP layer, configuration, and error handling used by individual API packages.

## Installation

```bash
composer require yebto/api-client
```

> You typically don't install this package directly. It's automatically included as a dependency of individual API packages like `yebto/currency-api`, `yebto/text-api`, etc.

## Available API Packages

| Package | Description |
|---------|-------------|
| [yebto/currency-api](https://github.com/yebto/currency-api) | Exchange rates, conversion, platform fees |
| [yebto/text-api](https://github.com/yebto/text-api) | Translate, rephrase, correct, summarize |
| [yebto/short-links-api](https://github.com/yebto/short-links-api) | URL shortening and analytics |
| [yebto/qrcode-api](https://github.com/yebto/qrcode-api) | QR code generation and experiences |
| [yebto/screenshot-api](https://github.com/yebto/screenshot-api) | Website screenshots, PDF, video capture |
| [yebto/watermark-api](https://github.com/yebto/watermark-api) | Image, PDF, video watermarking |
| [yebto/bot-detect-api](https://github.com/yebto/bot-detect-api) | Bot and crawler detection |
| [yebto/mail-checker-api](https://github.com/yebto/mail-checker-api) | Email validation |
| [yebto/device-analyzer-api](https://github.com/yebto/device-analyzer-api) | User-agent device detection |
| [yebto/domain-api](https://github.com/yebto/domain-api) | Domain analysis and niche detection |
| [yebto/vat-api](https://github.com/yebto/vat-api) | VAT calculation |
| [yebto/pdf-builder-api](https://github.com/yebto/pdf-builder-api) | PDF generation from prompt or image |
| [yebto/invoicing-api](https://github.com/yebto/invoicing-api) | Invoice, receipt, proforma generation |
| [yebto/place-api](https://github.com/yebto/place-api) | Location search by name or coordinates |
| [yebto/html-generator-api](https://github.com/yebto/html-generator-api) | HTML block and document generation |
| [yebto/article-generator-api](https://github.com/yebto/article-generator-api) | AI article generation |
| [yebto/horoscope-api](https://github.com/yebto/horoscope-api) | Daily, weekly, monthly horoscopes |
| [yebto/numerology-api](https://github.com/yebto/numerology-api) | Numerology readings and predictions |
| [yebto/astrology-api](https://github.com/yebto/astrology-api) | Natal charts, transits, synastry |

## Error Handling

All API packages throw typed exceptions:

```php
use Yebto\ApiClient\Exceptions\ApiException;
use Yebto\ApiClient\Exceptions\AuthenticationException;
use Yebto\ApiClient\Exceptions\RateLimitException;

try {
    $result = $api->someMethod();
} catch (AuthenticationException $e) {
    // Missing or invalid API key (401)
} catch (RateLimitException $e) {
    // Too many requests (429)
} catch (ApiException $e) {
    $e->getMessage();       // Error message
    $e->getHttpCode();      // HTTP status code
    $e->getResponseBody();  // Full response array
}
```

## Free API Access

Register at [yeb.to](https://yeb.to) with Google OAuth to get a free API key with 1000+ requests included.

## Support

- API Documentation: [docs.yeb.to](https://docs.yeb.to)
- Email: support@yeb.to
- Issues: [GitHub Issues](https://github.com/yebto/api-client/issues)

## License

MIT - NETOX Ltd.
