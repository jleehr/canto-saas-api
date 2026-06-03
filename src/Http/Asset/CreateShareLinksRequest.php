<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

use Fairway\CantoSaasApi\Http\Request;

final class CreateShareLinksRequest extends Request
{
    private ?string $expires;
    private bool $hideShareBy;
    private bool $allowDownloadOriginal;
    private bool $allowCropAndResize;
    private bool $allowPresets;
    private bool $displayMetadata;
    /** @var array{id: string, scheme: string}[]  */
    private array $contents;

    /**
     * @param string|null $expires If null, never expired.
     * @param bool $hideShareBy If true, the shared by user will be hidden
     * @param bool $allowDownloadOriginal If true, downloading Original file is available. If false, image can be downloaded as converted content.
     * @param bool $allowCropAndResize If true, the download page will show crop and resize options.
     * @param bool $allowPresets If true, can be downloaded as a preset.
     * @param bool $displayMetadata If true, user can view metadata.
     */
    public function __construct(?string $expires = null, bool $hideShareBy = true, bool $allowDownloadOriginal = true, bool $allowCropAndResize = true, bool $allowPresets = true, bool $displayMetadata = false)
    {
        $this->expires = $expires;
        $this->hideShareBy = $hideShareBy;
        $this->allowDownloadOriginal = $allowDownloadOriginal;
        $this->allowCropAndResize = $allowCropAndResize;
        $this->allowPresets = $allowPresets;
        $this->displayMetadata = $displayMetadata;
    }

    public function addContent(string $id, string $scheme): self
    {
        $this->contents[$id] = [
            'id' => $id,
            'scheme' => $scheme,
        ];
        return $this;
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function getApiPath(): string
    {
        return 'share/batch';
    }

    public function getMethod(): string
    {
        return self::PUT;
    }

    public function jsonSerialize(): array
    {
        return [
            'shareDetail' => [
                'expires' => $this->expires,
                'hideShareBy' => $this->hideShareBy,
                'allowDownloadOriginal' => $this->allowDownloadOriginal,
                'allowCropAndResize' => $this->allowCropAndResize,
                'displayMetadata' => $this->displayMetadata,
                'allowPresets' => $this->allowPresets,
            ],
            'contents' => array_values($this->contents),
        ];
    }
}
