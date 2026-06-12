<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

final class RenameContentRequest extends \Fairway\CantoSaasApi\Http\Request
{
    private string $scheme;
    private string $contentId;
    private string $newName;

    public function __construct(string $scheme, string $contentId, string $newName)
    {
        $this->scheme = $scheme;
        $this->contentId = $contentId;
        $this->newName = $newName;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->scheme,
            $this->contentId,
            'rename',
        ];
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->newName,
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
