# PHP client for Canto API

A PHP library to interact with the [Canto SaaS API](https://www.canto.com/).

This library was originally developed by [eCentral](https://github.com/ecentral)
under the package name `fairway/canto-saas-api`. The original repository is no
longer available; this is the maintained continuation, published as
`jleehr/canto-saas-api`. The PHP namespace `Fairway\CantoSaasApi` is kept for
backward compatibility.

## Installation

```bash
composer require jleehr/canto-saas-api
```

## Example usage

```php
use Fairway\CantoSaasApi\ClientOptions;
use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeRequest;

$clientOptions = new ClientOptions([
    'cantoName' => 'my-canto-name',
    'cantoDomain' => 'canto.de',
    'appId' => '123456789',
    'appSecret' => 'my-app-secret',
]);
$client = new Client($clientOptions);
$accessToken = $client->authorizeWithClientCredentials('my-user@email.com')
                      ->getAccessToken();
$client->setAccessToken($accessToken);
$allFolders = $client->libraryTree()
                     ->getTree(new GetTreeRequest())
                     ->getResults();
```

## Security notes

- The `httpClientOptions => ['debug' => true]` option passes Guzzle's debug
  mode through, which writes the complete HTTP traffic — including the
  `Authorization` header and OAuth credentials — to STDOUT. Use it for local
  development only, never in production. If you need custom HTTP behavior,
  inject a preconfigured client via the `httpClient` option instead.
- Values that end up in the request path (scheme, folder or album type, IDs)
  are validated before the request URL is built. An api path segment must
  consist of `A-Z a-z 0-9 . _ ~ -` and must not be `.` or `..`. A path
  variable is percent-encoded and rejected if it contains a `.` or `..`
  segment — also in percent-encoded form, since a server may decode the URL
  before it resolves the path. Dots inside a segment (`file..name`) stay
  valid, a control character or invalid UTF-8 does not. Other valid UTF-8
  stays usable, so keywords or file names with umlauts still work.
  Invalid values raise an `InvalidRequestException` instead of
  silently addressing a different endpoint. Pass request parameters through
  with that in mind: a manipulated value now produces an error, it is not
  sanitized away. The check covers the URL this library builds; how a server
  normalizes or decodes the path it receives is outside its reach.
- See [SECURITY.md](SECURITY.md) for how to report vulnerabilities.

## License

MIT. See [LICENSE](LICENSE). Original work © eCentral GmbH.

## Trademark notice

Canto is a trademark of Canto, Inc. This package is an independent,
unofficial client library and is not affiliated with or endorsed by Canto.
