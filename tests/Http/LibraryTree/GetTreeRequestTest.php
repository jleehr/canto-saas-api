<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetTreeRequestTest extends TestCase
{
    #[Test]
    public function createRequestWithDefaultConfig(): void
    {
        $request = new GetTreeRequest();
        $expected = [
            'sortBy' => 'time',
            'sortDirection' => 'ascending',
            'layer' => -1,
        ];

        self::assertSame($expected, $request->getQueryParams());
        self::assertNull($request->getPathVariables());
    }

    #[Test]
    public function setSort(): void
    {
        $request = new GetTreeRequest();
        $request->setSortBy('name');

        self::assertSame('name', $request->getQueryParams()['sortBy']);
    }

    #[Test]
    public function setSortDirection(): void
    {
        $request = new GetTreeRequest();
        $request->setSortDirection('descending');

        self::assertSame('descending', $request->getQueryParams()['sortDirection']);
    }

    #[Test]
    public function setLayer(): void
    {
        $request = new GetTreeRequest();
        $request->setLayer(3);

        self::assertSame(3, $request->getQueryParams()['layer']);
    }
}
