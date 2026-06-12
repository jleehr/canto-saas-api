<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTreeResponseTest extends TestCase
{
    #[Test]
    public function createValidResponse(): void
    {
        $response = new GetTreeResponse(
            new Response(
                200,
                [],
                '{"results":[{"id":"test"}],"sortBy":"time","sortDirection":"descending"}'
            )
        );

        self::assertSame([['id' => 'test']], $response->getResults());
        self::assertSame('time', $response->getSortBy());
        self::assertSame('descending', $response->getSortDirection());
    }

    #[Test]
    public function throwExceptionWithEmptyBody(): void
    {
        self::expectExceptionCode(1626434956);

        new GetTreeResponse(new Response(200, [], ''));
    }

    #[Test]
    public function throwExceptionWithInvalidJsonBody(): void
    {
        self::expectExceptionCode(1626434988);

        new GetTreeResponse(new Response(200, [], 'invalid-json'));
    }
}
