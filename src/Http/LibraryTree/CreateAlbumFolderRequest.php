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

final class CreateAlbumFolderRequest extends Request
{
    public const FOLDER = 'folder';
    public const ALBUM = 'album';

    private string $type;
    private string $name;
    private string $parentId = '';
    private string $description = '';

    public function __construct(string $name, string $type = self::FOLDER)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setParentFolder(string $parentFolderId): self
    {
        $this->parentId = $parentFolderId;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getQueryParams(): ?array
    {
        return [
            'description' => $this->description,
        ];
    }

    public function getPathVariables(): ?array
    {
        if ($this->type === self::FOLDER) {
            return [
                'parentFolderId' => $this->parentId,
                'folderName' => $this->name,
            ];
        }
        return [
            'parentFolderId' => $this->parentId,
            'albumName' => $this->name,
        ];
    }

    public function getApiPath(): string
    {
        return $this->type;
    }

    public function getMethod(): string
    {
        return self::POST;
    }
}
