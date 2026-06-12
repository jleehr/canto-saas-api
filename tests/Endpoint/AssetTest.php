<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Endpoint;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\ClientOptions;
use Fairway\CantoSaasApi\Endpoint\Asset;
use Fairway\CantoSaasApi\Http\Asset\BatchUpdatePropertiesRequest;
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

class AssetTest extends TestCase
{
    #[Test]
    public function batchUpdatePropertiesSuccessfulObtainResponse(): void
    {
        $mockHandler = new MockHandler([new Response(200, [], 'success')]);
        $clientMock = $this->buildClientMock($mockHandler);

        assert($clientMock instanceof Client);
        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $response = $assetEndpoint->batchUpdateProperties($request);

        self::assertSame('success', $response->getBody());
    }

    #[Test]
    public function batchUpdatePropertiesExpectNotAuthorizedException(): void
    {
        $this->expectExceptionCode(1626717511);

        $mockHandler = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('PUT', 'test'),
                new Response(401)
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $assetEndpoint->batchUpdateProperties($request);
    }

    #[Test]
    public function batchUpdatePropertiesExpectUnexpectedHttpStatusException(): void
    {
        $this->expectExceptionCode(1627649307);

        $mockHandler = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('PUT', 'test'),
                new Response(400, [], 'success')
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $assetEndpoint->batchUpdateProperties($request);
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
        $requestMock = $this->getMockBuilder(BatchUpdatePropertiesRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBody'])
            ->getMock();
        $requestMock->method('getBody')->willReturn('{"contents":[],"properties":[]}');
        return $requestMock;
    }
}
