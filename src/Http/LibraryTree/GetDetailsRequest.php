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

class GetDetailsRequest extends Request
{
    public const TYPE_FOLDER = 'folder';
    public const TYPE_ALBUM = 'album';

    protected string $folderId;

    protected string $type;

    public function __construct(string $folderId, string $type = self::TYPE_FOLDER)
    {
        $this->folderId = $folderId;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQueryParams(): ?array
    {
        return null;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->folderId,
        ];
    }

    public function getApiPath(): string
    {
        return $this->getType() === self::TYPE_FOLDER ? 'info/folder' : 'info/album';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
