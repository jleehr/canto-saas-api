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
use Fairway\CantoSaasApi\Http\Upload\UploadFileRequest;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UploadFileRequestTest extends TestCase
{
    #[Test]
    public function multipartUsesGivenFileName(): void
    {
        $request = new UploadFileRequest(__FILE__, $this->buildSettings());
        $request->setFileName('file-name.jpg');

        self::assertSame('file-name.jpg', $this->getFilePart($request)['filename']);
    }

    #[Test]
    public function multipartFileNameIsSanitized(): void
    {
        $request = new UploadFileRequest(__FILE__, $this->buildSettings());
        $request->setFileName("../\"evil\r\nname.jpg");

        self::assertSame('evilname.jpg', $this->getFilePart($request)['filename']);
    }

    private function getFilePart(UploadFileRequest $request): array
    {
        foreach ($request->getMultipart() as $part) {
            if ($part['name'] === 'file') {
                return $part;
            }
        }
        self::fail('Multipart data contains no file part.');
    }

    private function buildSettings(): GetUploadSettingResponse
    {
        return new GetUploadSettingResponse(new Response(200, [], json_encode([
            'Policy' => 'policy-1234',
            'Signature' => 'signature-1234',
            'AWSAccessKeyId' => 'aws-key-1234',
            'x-amz-meta-scheme' => 'image',
            'x-amz-meta-album_id' => 'album-1234',
            'acl' => 'private',
            'x-amz-meta-tag' => 'tag-1234',
            'x-amz-meta-id' => 'id-1234',
            'x-amz-meta-file_name' => 'settings-file-name.jpg',
            'url' => 'https://upload.example.com',
            'key' => 'key-1234',
        ], JSON_THROW_ON_ERROR)));
    }
}
