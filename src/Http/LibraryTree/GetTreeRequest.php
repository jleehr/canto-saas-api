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

class GetTreeRequest extends Request
{
    public const SORT_DIRECTION_ASC = 'ascending';
    public const SORT_DIRECTION_DESC = 'descending';
    public const SORT_BY_TIME = 'time';
    public const SORT_BY_NAME = 'name';
    public const SORT_BY_SCHEME = 'scheme';
    public const SORT_BY_OWNER = 'owner';
    public const SORT_BY_SIZE = 'size';

    protected string $folderId;

    protected string $sortBy = self::SORT_BY_TIME;

    protected string $sortDirection = self::SORT_DIRECTION_ASC;

    protected int $layer = -1;

    public function __construct(string $folderId = '')
    {
        $this->folderId = $folderId;
    }

    /**
     * @see SORT_BY_* constants.
     */
    public function setSortBy(string $sortBy): GetTreeRequest
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @see SORT_DIRECTION_* constants.
     */
    public function setSortDirection(string $sortDirection): GetTreeRequest
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function setLayer(int $layer): GetTreeRequest
    {
        $this->layer = $layer;
        return $this;
    }

    public function getQueryParams(): ?array
    {
        return [
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'layer' => $this->layer
        ];
    }

    public function getPathVariables(): ?array
    {
        if ($this->folderId !== '') {
            return [
                $this->folderId,
            ];
        }
        return null;
    }

    public function getApiPath(): string
    {
        return 'tree';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
