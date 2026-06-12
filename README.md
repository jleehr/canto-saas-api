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
- See [SECURITY.md](SECURITY.md) for how to report vulnerabilities.

## License

MIT. See [LICENSE](LICENSE). Original work © eCentral GmbH.
