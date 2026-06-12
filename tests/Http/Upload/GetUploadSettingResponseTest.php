<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Upload;

use Fairway\CantoSaasApi\Http\Upload\GetUploadSettingResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetUploadSettingResponseTest extends TestCase
{
    #[Test]
    public function responseFieldsAreMappedCorrectly(): void
    {
        $response = new GetUploadSettingResponse(new Response(200, [], json_encode([
            'Policy' => 'policy-1234',
            'Signature' => 'signature-1234',
            'AWSAccessKeyId' => 'aws-key-1234',
            'x-amz-meta-scheme' => 'image',
            'x-amz-meta-album_id' => 'album-1234',
            'acl' => 'private',
            'x-amz-meta-tag' => 'tag-1234',
            'x-amz-meta-id' => 'id-1234',
            'x-amz-meta-file_name' => 'file-name.jpg',
            'url' => 'https://upload.example.com',
            'key' => 'key-1234',
        ], JSON_THROW_ON_ERROR)));

        self::assertSame('policy-1234', $response->getPolicy());
        self::assertSame('signature-1234', $response->getSignature());
        self::assertSame('aws-key-1234', $response->getAwsAccessKeyId());
        self::assertSame('image', $response->getXAmzMetaScheme());
        self::assertSame('album-1234', $response->getXAmzMetaAlbumId());
        self::assertSame('private', $response->getAcl());
        self::assertSame('tag-1234', $response->getXAmzMetaTag());
        self::assertSame('id-1234', $response->getXAmzMetaId());
        self::assertSame('file-name.jpg', $response->getXAmzMetaFileName());
        self::assertSame('https://upload.example.com', $response->getUrl());
        self::assertSame('key-1234', $response->getKey());
    }
}
