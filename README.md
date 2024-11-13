# PHP client for Canto API

This library was originally maintained by [eCentral](https://github.com/ecentral) under the namespace fairway/canto-saas-api.

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
