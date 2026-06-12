<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\LibraryTree\SearchFolderRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SearchFolderRequestTest extends TestCase
{
    #[Test]
    public function createRequestWithDefaultConfig(): void
    {
        $request = new SearchFolderRequest('test');
        $expected = [
            'keyword' => '',
            'scheme' => '',
            'tags' => '',
            'keywords' => '',
            'approval' => '',
            'owner' => '',
            'fileSize' => '',
            'created' => '',
            'createdTime' => '',
            'uploadedTime' => '',
            'lastModified' => '',
            'dimension' => '',
            'resolution' => '',
            'orientation' => '',
            'duration' => '',
            'pageNumber' => '',
            'sortBy' => 'time',
            'sortDirection' => 'descending',
            'limit' => 100,
            'start' => 0,
            'exactMatch' => 'false'
        ];

        self::assertSame($expected, $request->getQueryParams());
        self::assertSame(['test'], $request->getPathVariables());
    }

    #[Test]
    public function setKeyword(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setKeyword('photo');

        self::assertSame('photo', $request->getQueryParams()['keyword']);
    }

    #[Test]
    public function setScheme(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setScheme('image|video');

        self::assertSame('image|video', $request->getQueryParams()['scheme']);
    }

    #[Test]
    public function setTags(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setTags('tag1|tag2+tag3');

        self::assertSame('tag1|tag2+tag3', $request->getQueryParams()['tags']);
    }

    #[Test]
    public function setKeywords(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setKeywords('k1|k2+k3');

        self::assertSame('k1|k2+k3', $request->getQueryParams()['keywords']);
    }

    #[Test]
    public function setApproval(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setApproval('approved|pending');

        self::assertSame('approved|pending', $request->getQueryParams()['approval']);
    }

    #[Test]
    public function setOwner(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setOwner('test@example.tld');

        self::assertSame('test@example.tld', $request->getQueryParams()['owner']);
    }

    #[Test]
    public function setFileSize(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setFileSize(10, 10000);

        self::assertSame('10..10000', $request->getQueryParams()['fileSize']);
    }

    #[Test]
    public function setCreated(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setCreated(1626672536, 1626772536);

        self::assertSame('1626672536..1626772536', $request->getQueryParams()['created']);
    }

    #[Test]
    public function setCreatedTime(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setCreatedTime(1626672536, 1626772536);

        self::assertSame('1626672536..1626772536', $request->getQueryParams()['createdTime']);
    }

    #[Test]
    public function setUploadedTime(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setUploadedTime(1626672536, 1626772536);

        self::assertSame('1626672536..1626772536', $request->getQueryParams()['uploadedTime']);
    }

    #[Test]
    public function setLastModified(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setLastModified(1626672536, 1626772536);

        self::assertSame('1626672536..1626772536', $request->getQueryParams()['lastModified']);
    }

    #[Test]
    public function setDimension(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setDimension(300, 2000);

        self::assertSame('300..2000', $request->getQueryParams()['dimension']);
    }

    #[Test]
    public function setResolution(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setResolution(72, 300);

        self::assertSame('72..300', $request->getQueryParams()['resolution']);
    }

    #[Test]
    public function setOrientation(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setOrientation('square');

        self::assertSame('square', $request->getQueryParams()['orientation']);
    }

    #[Test]
    public function setDuration(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setDuration(30, 600);

        self::assertSame('30..600', $request->getQueryParams()['duration']);
    }

    #[Test]
    public function setPageNumber(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setPageNumber(1, 5);

        self::assertSame('1..5', $request->getQueryParams()['pageNumber']);
    }

    #[Test]
    public function setSortBy(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setSortBy('name');

        self::assertSame('name', $request->getQueryParams()['sortBy']);
    }

    #[Test]
    public function setSortDirection(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setSortDirection('ascending');

        self::assertSame('ascending', $request->getQueryParams()['sortDirection']);
    }

    #[Test]
    public function setLimit(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setLimit(50);

        self::assertSame(50, $request->getQueryParams()['limit']);
    }

    #[Test]
    public function setStart(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setStart(5);

        self::assertSame(5, $request->getQueryParams()['start']);
    }

    #[Test]
    public function setExactMatch(): void
    {
        $request = new SearchFolderRequest('test');
        $request->setExactMatch(true);

        self::assertSame('true', $request->getQueryParams()['exactMatch']);
    }
}
