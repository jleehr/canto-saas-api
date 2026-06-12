<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi;

use Fairway\CantoSaasApi\Endpoint\Asset;
use Fairway\CantoSaasApi\Endpoint\Authorization\OAuth2;
use Fairway\CantoSaasApi\Endpoint\LibraryTree;
use Fairway\CantoSaasApi\Endpoint\Upload;
use Fairway\CantoSaasApi\Helper\MdcUrlHelper;
use Fairway\CantoSaasApi\Http\Authorization\OAuth2Request;
use Fairway\CantoSaasApi\Http\Authorization\OAuth2Response;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Client
{
    protected const API_VERSION = 'v1';
    protected const API_ROUTE = 'https://%s.%s/api';
    protected const MDC_ROUTE = 'https://%s/rendition/%s/%s';

    protected ClientOptions $options;

    protected LoggerInterface $logger;

    protected ClientInterface $httpClient;

    protected ?string $accessToken = null;

    public function __construct(ClientOptions $options)
    {
        $this->options = $options;
        $this->httpClient = $this->options->getHttpClient() ?? $this->buildHttpClient();
        $this->logger = $this->options->getLogger() ?? new NullLogger();
    }

    public function getOptions(): ClientOptions
    {
        return $this->options;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        if ($accessToken !== '') {
            $this->accessToken = $accessToken;
        }
    }

    /**
     * @throws Endpoint\Authorization\AuthorizationFailedException|Endpoint\Authorization\NotAuthorizedException
     */
    public function authorizeWithClientCredentials(string $userId = '', string $scope = OAuth2Request::SCOPE_ADMIN): OAuth2Response
    {
        $request = new OAuth2Request();
        $request->setAppId($this->options->getAppId())
            ->setAppSecret($this->options->getAppSecret())
            ->setRedirectUri($this->options->getRedirectUri())
            ->setScope($scope);
        if ($userId !== '') {
            $request->setUserId($userId);
        }

        $OAuth2 = new OAuth2($this);
        $response = $OAuth2->obtainAccessToken($request);
        $this->setAccessToken($response->getAccessToken());

        return $response;
    }

    public function asset(): Asset
    {
        return new Asset($this);
    }

    public function libraryTree(): LibraryTree
    {
        return new LibraryTree($this);
    }

    public function upload(): Upload
    {
        return new Upload($this);
    }

    public function mdc(): MdcUrlHelper
    {
        return new MdcUrlHelper($this);
    }

    public function getApiUrl(?string $path = null): string
    {
        $url = sprintf(
            self::API_ROUTE,
            $this->getOptions()->getCantoName(),
            $this->getOptions()->getCantoDomain(),
        );
        return sprintf(
            '%s/%s/%s',
            $url,
            self::API_VERSION,
            $path ?? ''
        );
    }

    public function getMdcUrl(string $assetId = '', string $scheme = 'image'): string
    {
        $path = '';
        if ($assetId) {
            $path = sprintf('%s_%s/', $scheme, $assetId);
        }
        return sprintf(
            self::MDC_ROUTE,
            $this->options->getMdcDomainName(),
            $this->options->getMdcAwsAccountId(),
            $path,
        );
    }

    protected function buildHttpClient(): ClientInterface
    {
        $httpClientOptions = $this->options->getHttpClientOptions();
        return new \GuzzleHttp\Client([
            'allow_redirects' => true,
            'connect_timeout' => (int)$httpClientOptions['timeout'],
            // Also enforce a total request timeout; otherwise a server that
            // accepts the connection but never responds blocks the caller
            // indefinitely.
            'timeout' => (int)$httpClientOptions['timeout'],
            'debug' => (bool)$httpClientOptions['debug'],
            'headers' => [
                'userAgent' => $httpClientOptions['userAgent'],
            ],
        ]);
    }
}
