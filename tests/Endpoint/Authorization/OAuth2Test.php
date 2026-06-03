<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Endpoint\Authorization;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\ClientOptions;
use Fairway\CantoSaasApi\Endpoint\Authorization\OAuth2;
use Fairway\CantoSaasApi\Http\Authorization\OAuth2Request;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class OAuth2Test extends TestCase
{
    #[Test]
    public function successfulObtainAccessToken(): void
    {
        $mockHandler = new MockHandler([
            new Response(
                200,
                [],
                '{"accessToken":"access-token-1234","expiresIn":3600,"tokenType":"Bearer","refreshToken":"refresh-token-1234"}'
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $oAuth2 = new OAuth2($clientMock);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);
        $response = $oAuth2->obtainAccessToken($oAuthRequest);

        self::assertSame('access-token-1234', $response->getAccessToken());
        self::assertSame(3600, $response->getExpiresIn());
        self::assertSame('Bearer', $response->getTokenType());
        self::assertSame('refresh-token-1234', $response->getRefreshToken());
    }

    #[Test]
    public function obtainAccessTokenWithInvalidHttpResponseStatusCode(): void
    {
        $this->expectExceptionCode(1626447895);

        $mockHandler = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('POST', 'test'),
                new Response(400)
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $oAuth2 = new OAuth2($clientMock);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);
        $oAuth2->obtainAccessToken($oAuthRequest);
    }

    protected function buildClientMock(MockHandler $mockHandler): MockObject
    {
        $optionsMock = $this->getMockBuilder(ClientOptions::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCantoName', 'getCantoDomain'])
            ->getMock();
        $optionsMock->method('getCantoName')->willReturn('test');
        $optionsMock->method('getCantoDomain')->willReturn('canto.com');

        $httpClient = new HttpClient([
            'handler' => HandlerStack::create($mockHandler),
        ]);

        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHttpClient', 'getLogger', 'getOptions', 'getAccessToken'])
            ->getMock();
        $clientMock->method('getHttpClient')->willReturn($httpClient);
        $clientMock->method('getLogger')->willReturn(new NullLogger());
        $clientMock->method('getOptions')->willReturn($optionsMock);
        $clientMock->method('getAccessToken')->willReturn(null);

        return $clientMock;
    }

    protected function buildRequestMock(): MockObject
    {
        $requestMock = $this->getMockBuilder(OAuth2Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQueryParams'])
            ->getMock();
        $requestMock->method('getQueryParams')->willReturn([
            'app_id' => 'app-id-1234',
            'app_secret' => 'app-secret-1234',
            'grant_type' => 'client_credentials',
            'redirect_uri' => 'http://localhost',
            'code' => 'code-1234',
            'refresh_token' => 'refresh-token-1234',
            'scope' => 'admin',
            'user_id' => 'user@example.tld',
        ]);

        return $requestMock;
    }
}
