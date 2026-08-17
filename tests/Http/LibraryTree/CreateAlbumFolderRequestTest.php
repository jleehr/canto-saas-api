<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\LibraryTree;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Http\InvalidRequestException;
use Fairway\CantoSaasApi\Http\LibraryTree\CreateAlbumFolderRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CreateAlbumFolderRequestTest extends TestCase
{
    #[Test]
    public function validTypeBuildsExpectedUrl(): void
    {
        $request = (new CreateAlbumFolderRequest('album-name', CreateAlbumFolderRequest::ALBUM))
            ->setParentFolder('folder-1234');
        $httpRequest = $request->toHttpRequest($this->buildClientMock());

        self::assertSame(
            'https://test.canto.com/api/v1/album/folder-1234/album-name?description=',
            (string)$httpRequest->getUri()
        );
    }

    /**
     * Security: the type ends up in the request path, so a traversal in it
     * must not be able to address another endpoint.
     */
    #[Test]
    public function typeWithTraversalThrows(): void
    {
        $request = (new CreateAlbumFolderRequest('album-name'))
            ->setType('album/../../batch/delete');

        $this->expectException(InvalidRequestException::class);

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
