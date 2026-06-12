<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

use Fairway\CantoSaasApi\Http\Request;

final class AttachTagToContentRequest extends Request
{
    private string $scheme;
    private string $contentId;
    private string $tag;

    public function __construct(string $scheme, string $contentId, string $tag)
    {
        $this->scheme = $scheme;
        $this->contentId = $contentId;
        $this->tag = $tag;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->scheme,
            $this->contentId,
            'tag',
            $this->tag,
        ];
    }

    public function getApiPath(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return self::PUT;
    }
}
