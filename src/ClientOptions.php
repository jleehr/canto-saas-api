<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi;

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;

/**
 * @internal
 *
 * Note on httpClientOptions['debug']: when enabled, Guzzle/cURL writes the
 * complete HTTP traffic - including the Authorization header and OAuth
 * credentials - to STDOUT. Use for local development only, never in
 * production. For advanced needs inject a preconfigured 'httpClient' instead.
 */
class ClientOptions
{
    public const OPTIONS_SHAPE = [
        'cantoName' => 'string',
        'cantoDomain' => 'string',
        'appId' => 'string',
        'appSecret' => 'string',
        'redirectUri' => 'string',
        'mdcDomainName' => 'string',
        'mdcAwsAccountId' => 'string',
        'masterImageSize' => 'int',
        'httpClient' => ClientInterface::class,
        'httpClientOptions' => [
            'debug' => 'bool',
            'timeout' => 'int',
            'userAgent' => 'string',
        ],
        'logger' => LoggerInterface::class
    ];

    private string $cantoName;

    private string $cantoDomain;

    private string $appId;

    private string $appSecret;

    private string $redirectUri;

    private string $mdcDomainName;

    private string $mdcAwsAccountId;

    private int $masterImageSize;

    private ?ClientInterface $httpClient;

    private array $httpClientOptions;

    private ?LoggerInterface $logger;

    /**
     * @throws InvalidArgumentException
     */
    #[ArrayShape(self::OPTIONS_SHAPE)]
    public function __construct(array $options = [])
    {
        $this->validateOptions($options);

        $this->cantoName = $options['cantoName'];
        $this->cantoDomain = $options['cantoDomain'] ?? 'canto.com';
        $this->appId = $options['appId'];
        $this->appSecret = $options['appSecret'];
        $this->redirectUri = $options['redirectUri'] ?? '';
        $this->httpClient = $options['httpClient'] ?? null;
        $this->httpClientOptions = array_replace(
            [
                'debug' => false,
                'timeout' => 10,
                'userAgent' => 'Canto PHP API client',
            ],
            $options['httpClientOptions'] ?? []
        );
        $this->logger = $options['logger'] ?? null;
        $this->mdcAwsAccountId = $options['mdcAwsAccountId'] ?? '';
        $this->mdcDomainName = $options['mdcDomainName'] ?? '';
        $this->masterImageSize = $options['masterImageSize'] ?? 2048;
    }

    public function getCantoName(): string
    {
        return $this->cantoName;
    }

    public function getCantoDomain(): string
    {
        return $this->cantoDomain;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    public function getHttpClientOptions(): array
    {
        return $this->httpClientOptions;
    }

    public function getMdcDomainName(): string
    {
        return $this->mdcDomainName;
    }

    public function getMdcAwsAccountId(): string
    {
        return $this->mdcAwsAccountId;
    }

    public function getMasterImageSize(): int
    {
        return $this->masterImageSize;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validateOptions(array $options): void
    {
        if (is_string($options['cantoName'] ?? null) === false || $options['cantoName'] === '') {
            throw new InvalidArgumentException(
                'Option cantoName is not given or not a string.',
                1626849038
            );
        }
        if (is_string($options['appId'] ?? null) === false || $options['appId'] === '') {
            throw new InvalidArgumentException(
                'Option appId is not given or not a string.',
                1626849132
            );
        }
        if (is_string($options['appSecret'] ?? null) === false || $options['appSecret'] === '') {
            throw new InvalidArgumentException(
                'Option appSecret is not given or not a string.',
                1626849145
            );
        }
    }
}
