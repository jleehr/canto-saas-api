<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http;

use Fairway\CantoSaasApi\Client;
use GuzzleHttp\Psr7\Request as HttpRequest;

abstract class UploadRequest implements RequestInterface
{
    abstract public function getFormData(): array;
    abstract public function uploadedFilePath(): string;

    public function getQueryParams(): ?array
    {
        return null;
    }

    public function getPathVariables(): ?array
    {
        return null;
    }

    public function getMethod(): string
    {
        return self::POST;
    }

    public function getMultipart(): array
    {
        $formData = $this->getFormData();
        if (class_exists(\GuzzleHttp\Psr7\Utils::class) && method_exists(\GuzzleHttp\Psr7\Utils::class, 'tryFopen')) {
            $formData['file'] = \GuzzleHttp\Psr7\Utils::tryFopen($this->uploadedFilePath(), 'r');
        } elseif (function_exists('\GuzzleHttp\Psr7\try_fopen')) {
            // Backward compatibility for guzzlehttp/psr7 < 1.8
            $formData['file'] = \GuzzleHttp\Psr7\try_fopen($this->uploadedFilePath(), 'r');
        } else {
            throw new \Exception('Could not open file');
        }
        $multipart = [];
        foreach ($formData as $key => $value) {
            $data = [
                'name' => $key,
                'contents' => $value,
            ];
            if ($key === 'file') {
                $data['filename'] = $this->sanitizeFileName((string)$formData['x-amz-meta-file_name']);
            }
            $multipart[] = $data;
        }
        return $multipart;
    }

    /**
     * The file name ends up in the Content-Disposition header of the
     * multipart part. Strip path segments, quotes and control characters
     * (CR/LF) so a user-controlled name cannot manipulate the header.
     */
    private function sanitizeFileName(string $fileName): string
    {
        $fileName = basename($fileName);
        return preg_replace('/[\x00-\x1F\x7F"\\\\]/', '', $fileName) ?? '';
    }

    public function toHttpRequest(Client $client, array $withHeaders = []): HttpRequest
    {
        return new HttpRequest(
            $this->getMethod(),
            $this->getApiPath(),
            $withHeaders,
            null,
        );
    }
}
