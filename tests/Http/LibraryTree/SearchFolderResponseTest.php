<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\LibraryTree\SearchFolderResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SearchFolderResponseTest extends TestCase
{
    #[Test]
    public function createValidResponse(): void
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
        $response = new SearchFolderResponse(new Response(200, [], $responseBody));

        self::assertSame([['key' => 'facet-value']], $response->getFacets());
        self::assertSame([['result-id' => 1234]], $response->getResults());
        self::assertSame(100, $response->getLimit());
        self::assertSame(500, $response->getFound());
        self::assertSame('name', $response->getSortBy());
        self::assertSame('ascending', $response->getSortDirection());
        self::assertSame('test', $response->getMatchExpr());
    }

    #[Test]
    public function throwExceptionWithEmptyBody(): void
    {
        self::expectExceptionCode(1626434956);

        new SearchFolderResponse(new Response(200, [], ''));
    }

    #[Test]
    public function throwExceptionWithInvalidJsonBody(): void
    {
        self::expectExceptionCode(1626434988);

        new SearchFolderResponse(new Response(200, [], 'invalid-json'));
    }
}
