<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\Request;

final class DeleteFolderOrAlbumRequest extends Request
{
    private array $folder;

    public function addFolder(string $id, string $scheme): self
    {
        $this->folder[$id] = [
            'id' => $id,
            'scheme' => $scheme,
        ];
        return $this;
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return array_values($this->folder);
    }

    public function getApiPath(): string
    {
        return 'batch/folder';
    }

    public function getMethod(): string
    {
        return self::DELETE;
    }
}
