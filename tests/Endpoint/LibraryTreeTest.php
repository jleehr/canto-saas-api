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
use Fairway\CantoSaasApi\Endpoint\LibraryTree;
use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\SearchFolderRequest;
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

class LibraryTreeTest extends TestCase
{
    #[Test]
    public function searchFolderContentSuccessfulObtainResponse(): void
    {
        $responseBody = '{' .
            '"facets":[{"key":"facet-value"}],' .
            '"results":[{"result-id":1234}],' .
            '"limit":100,' .
            '"found":500,' .
            '"sortBy":"name",' .
            '"sortDirection":"ascending",' .
            '"matchExpr":"test"' .
            '}';
        $mockHandler = new MockHandler([new Response(200, [], $responseBody)]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $searchFolderRequest = $this->buildListFolderContentRequestMock();
        assert($searchFolderRequest instanceof SearchFolderRequest);
        $response = $libraryTreeEndpoint->searchFolderContent($searchFolderRequest);

        self::assertSame([['key' => 'facet-value']], $response->getFacets());
        self::assertSame([['result-id' => 1234]], $response->getResults());
        self::assertSame(100, $response->getLimit());
        self::assertSame(500, $response->getFound());
        self::assertSame('name', $response->getSortBy());
        self::assertSame('ascending', $response->getSortDirection());
        self::assertSame('test', $response->getMatchExpr());
    }

    #[Test]
    public function searchFolderContentExpectNotAuthorizedException(): void
    {
        $this->expectExceptionCode(1626717511);

        $mockHandler = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', 'test'),
                new Response(401, [], '[]')
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $searchFolderRequest = $this->buildListFolderContentRequestMock();
        assert($searchFolderRequest instanceof SearchFolderRequest);
        $libraryTreeEndpoint->searchFolderContent($searchFolderRequest);
    }

    #[Test]
    public function searchFolderContentExpectUnexpectedHttpStatusException(): void
    {
        $this->expectExceptionCode(1627649307);

        $mockHandler = new MockHandler([
            new RequestException(
                'Error Communicating with Server',
                new Request('GET', 'test'),
                new Response(400, [], '[]')
            )
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $searchFolderRequest = $this->buildListFolderContentRequestMock();
        assert($searchFolderRequest instanceof SearchFolderRequest);
        $libraryTreeEndpoint->searchFolderContent($searchFolderRequest);
    }

    #[Test]
    public function getTreeSuccessfulObtainResponse(): void
    {
        $responseBody = '{"results":[{"id":"test"}],"sortBy":"time","sortDirection":"descending"}';
        $mockHandler = new MockHandler([new Response(200, [], $responseBody)]);
        $clientMock = $this->buildClientMock($mockHandler);

        assert($clientMock instanceof Client);
        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $requestMock = $this->buildGetTreeRequestMock();
        assert($requestMock instanceof GetTreeRequest);
        $response = $libraryTreeEndpoint->getTree($requestMock);

        self::assertSame([['id' => 'test']], $response->getResults());
        self::assertSame('time', $response->getSortBy());
        self::assertSame('descending', $response->getSortDirection());
    }

    #[Test]
    public function getTreeExpectNotAuthorizedException(): void
    {
        $this->expectExceptionCode(1626717511);

        $mockHandler = new MockHandler([new Response(401, [], '[]')]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $treeRequest = $this->buildGetTreeRequestMock();
        assert($treeRequest instanceof GetTreeRequest);
        $libraryTreeEndpoint->getTree($treeRequest);
    }

    #[Test]
    public function getTreeExpectUnexpectedHttpStatusException(): void
    {
        $this->expectExceptionCode(1627649307);

        $mockHandler = new MockHandler([new Response(400, [], '[]')]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $libraryTreeEndpoint = new LibraryTree($clientMock);
        $treeRequest = $this->buildGetTreeRequestMock();
        assert($treeRequest instanceof GetTreeRequest);
        $libraryTreeEndpoint->getTree($treeRequest);
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

    protected function buildListFolderContentRequestMock(): MockObject
    {
        $requestMock = $this->getMockBuilder(SearchFolderRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQueryParams', 'getPathVariables'])
            ->getMock();
        $requestMock->method('getQueryParams')->willReturn(null);
        $requestMock->method('getPathVariables')->willReturn(null);
        return $requestMock;
    }

    protected function buildGetTreeRequestMock(): MockObject
    {
        $requestMock = $this->getMockBuilder(GetTreeRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQueryParams', 'getPathVariables'])
            ->getMock();
        $requestMock->method('getQueryParams')->willReturn(null);
        $requestMock->method('getPathVariables')->willReturn(null);
        return $requestMock;
    }
}
