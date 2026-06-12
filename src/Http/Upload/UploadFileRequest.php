<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Upload;

use Fairway\CantoSaasApi\Http\UploadRequest;

/**
 * Before uploading file @see Upload::getUploadSetting() is required.
 */
final class UploadFileRequest extends UploadRequest
{
    public GetUploadSettingResponse $settings;
    private string $filePath;
    private ?string $referId;

    private ?string $scheme = null;
    private ?string $metaId = null;
    private ?string $fileName = null;
    private ?string $tag = null;
    private ?string $albumId = null;

    public function __construct(string $filePath, GetUploadSettingResponse $settings, ?string $referId = null)
    {
        $this->filePath = $filePath;
        $this->settings = $settings;
        $this->referId = $referId;
    }

    public function getApiPath(): string
    {
        return $this->settings->getUrl();
    }

    public function getScheme(): string
    {
        return $this->scheme ?? $this->settings->getXAmzMetaScheme();
    }

    /**
     * If you want to update an existing asset, value should be the scheme of the existing asset.
     * In the Query upload status API, it will return this field value, if you have set this value.
     * Leave this parameter empty if you want to upload a new asset.
     * @param string $scheme
     * @return $this
     */
    public function setScheme(string $scheme): UploadFileRequest
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function getMetaId(): string
    {
        return $this->metaId ?? '';
    }

    /**
     * If you want to update an existing asset, value should be the id of the existing asset.
     * In the Query upload status API, it will return this field value, if you have set this value.
     * Leave this parameter empty if you want to upload a new asset.
     * @param string $metaId
     * @return $this
     */
    public function setMetaId(string $metaId): UploadFileRequest
    {
        $this->metaId = $metaId;
        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName ?? $this->settings->getXAmzMetaFileName();
    }

    /**
     * The name of the file (with extension).
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): UploadFileRequest
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getTag(): string
    {
        return $this->tag ?? $this->settings->getXAmzMetaTag();
    }

    public function setTag(string $tag): UploadFileRequest
    {
        $this->tag = $tag;
        return $this;
    }

    public function getAlbumId(): string
    {
        return $this->albumId ?? $this->settings->getXAmzMetaAlbumId();
    }

    /**
     * If the asset should be assigned to an existing album, value should be the album id of this album.
     * Leave this parameter empty if the asset should not be assigned to any album.
     * @param string $albumId
     * @return $this
     */
    public function setAlbumId(string $albumId): UploadFileRequest
    {
        $this->albumId = $albumId;
        return $this;
    }

    public function getFormData(): array
    {
        $formData = [
            'key' => $this->settings->getKey(),
            'acl' => $this->settings->getAcl(),
            'AWSAccessKeyId' => $this->settings->getAwsAccessKeyId(),
            'Policy' => $this->settings->getPolicy(),
            'Signature' => $this->settings->getSignature(),
            'x-amz-meta-scheme' => $this->getScheme(),
            'x-amz-meta-album_id' => $this->getAlbumId(),
            'x-amz-meta-tag' => $this->getTag(),
            'x-amz-meta-id' => $this->getMetaId(),
            'x-amz-meta-file_name' => $this->getFileName(),
        ];
        if ($this->settings->isDeEnvironment()) {
            unset(
                $formData['AWSAccessKeyId'],
                $formData['Signature'],
            );
            $formData['x-amz-date'] = $this->settings->getXAmzDate();
            $formData['x-amz-algorithm'] = $this->settings->getXAmzAlgorithm();
            $formData['x-amz-credential'] = $this->settings->getXAmzCredential();
            $formData['x-amz-Signature'] = $this->settings->getXAmzSignature();
        }
        if ($this->referId) {
            $formData['x-amz-meta-refer_id'] = $this->referId;
        }
        return $formData;
    }

    public function uploadedFilePath(): string
    {
        return $this->filePath;
    }
}
