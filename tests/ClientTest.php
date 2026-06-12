<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\ClientOptions;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ClientTest extends TestCase
{
    #[Test]
    public function createObjectWithDefaultOptions(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
        ]);
        $client = new Client($options);

        self::assertInstanceOf(ClientInterface::class, $client->getHttpClient());
        self::assertInstanceOf(LoggerInterface::class, $client->getLogger());
    }

    #[Test]
    public function builtHttpClientUsesConnectAndRequestTimeout(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'httpClientOptions' => [
                'timeout' => 23,
            ],
        ]);
        $client = new Client($options);

        $httpClient = $client->getHttpClient();
        assert($httpClient instanceof \GuzzleHttp\Client);
        self::assertSame(23, $httpClient->getConfig('connect_timeout'));
        self::assertSame(23, $httpClient->getConfig('timeout'));
    }

    #[Test]
    public function createObjectWithCustomHttpClient(): void
    {
        $clientOptionsMock = $this->getMockBuilder(ClientOptions::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHttpClient', 'getLogger'])
            ->getMock();
        $clientOptionsMock->method('getHttpClient')->willReturn(new \GuzzleHttp\Client());
        $clientOptionsMock->method('getLogger')->willReturn(null);
        $client = new Client($clientOptionsMock);

        self::assertInstanceOf(\GuzzleHttp\Client::class, $client->getHttpClient());
    }

    #[Test]
    public function createObjectWithCustomLogger(): void
    {
        $clientOptionsMock = $this->getMockBuilder(ClientOptions::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHttpClient', 'getHttpClientOptions', 'getLogger'])
            ->getMock();
        $clientOptionsMock->method('getHttpClient')->willReturn(null);
        $clientOptionsMock->method('getHttpClientOptions')->willReturn([
            'debug' => false,
            'timeout' => 10,
            'userAgent' => 'test',
        ]);
        $clientOptionsMock->method('getLogger')->willReturn(new NullLogger());
        $client = new Client($clientOptionsMock);

        self::assertInstanceOf(NullLogger::class, $client->getLogger());
    }
}
