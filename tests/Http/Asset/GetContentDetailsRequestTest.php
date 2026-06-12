<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Asset;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Http\Asset\GetContentDetailsRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetContentDetailsRequestTest extends TestCase
{
    #[Test]
    public function pathVariablesAreAppendedToUrl(): void
    {
        $request = new GetContentDetailsRequest('content-id-1234', 'image');
        $httpRequest = $request->toHttpRequest($this->buildClientMock());

        self::assertSame(
            'https://test.canto.com/api/v1/image/content-id-1234',
            (string)$httpRequest->getUri()
        );
    }

    #[Test]
    public function pathVariablesAreUrlEncoded(): void
    {
        $request = new GetContentDetailsRequest('../../evil?x=1#y', 'image');
        $httpRequest = $request->toHttpRequest($this->buildClientMock());

        self::assertSame(
            'https://test.canto.com/api/v1/image/..%2F..%2Fevil%3Fx%3D1%23y',
            (string)$httpRequest->getUri()
        );
    }

    private function buildClientMock(): Client
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getApiUrl'])
            ->getMock();
        $clientMock->method('getApiUrl')->willReturn('https://test.canto.com/api/v1/');

        return $clientMock;
    }
}
