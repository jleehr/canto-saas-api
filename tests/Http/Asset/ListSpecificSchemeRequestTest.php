<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Asset;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Http\Asset\ListSpecificSchemeRequest;
use Fairway\CantoSaasApi\Http\InvalidRequestException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ListSpecificSchemeRequestTest extends TestCase
{
    #[Test]
    public function validSchemeBuildsExpectedUrl(): void
    {
        $request = (new ListSpecificSchemeRequest('image'))->setTagsLiteral('tag-1234');
        $httpRequest = $request->toHttpRequest($this->buildClientMock());

        self::assertSame(
            'https://test.canto.com/api/v1/image'
            . '?tagsLiteral=tag-1234&operator=and&exactMatch=false&sortBy=time&sortDirection=ascending&end=100',
            (string)$httpRequest->getUri()
        );
    }

    #[Test]
    public function freshlyConstructedRequestBuildsUrlWithoutTagsLiteral(): void
    {
        $request = new ListSpecificSchemeRequest('image');
        $httpRequest = $request->toHttpRequest($this->buildClientMock());

        self::assertSame(
            'https://test.canto.com/api/v1/image'
            . '?operator=and&exactMatch=false&sortBy=time&sortDirection=ascending&end=100',
            (string)$httpRequest->getUri()
        );
    }

    /**
     * Security: the scheme ends up in the request path, so a traversal in it
     * must not be able to address another endpoint.
     */
    #[Test]
    public function schemeWithTraversalThrows(): void
    {
        $request = new ListSpecificSchemeRequest('image/../../batch/delete');

        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionCode(1786924800);

        $request->toHttpRequest($this->buildClientMock());
    }

    private function buildClientMock(): Client
    {
        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getApiUrl'])
            ->getMock();
        $clientMock->method('getApiUrl')->willReturnCallback(
            static fn (?string $path = null): string => 'https://test.canto.com/api/v1/' . ($path ?? '')
        );

        return $clientMock;
    }
}
