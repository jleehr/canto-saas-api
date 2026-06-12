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

class ListAlbumContentRequest extends Request
{
    public const SORT_DIRECTION_ASC = 'ascending';
    public const SORT_DIRECTION_DESC = 'descending';
    public const SORT_BY_TIME = 'time';
    public const SORT_BY_NAME = 'name';
    public const SORT_BY_SCHEME = 'scheme';
    public const SORT_BY_OWNER = 'owner';
    public const SORT_BY_SIZE = 'size';

    protected string $albumId;

    protected string $sortBy = self::SORT_BY_TIME;

    protected string $sortDirection = self::SORT_DIRECTION_DESC;

    protected int $limit = 100;

    protected int $start = 0;

    public function __construct(string $albumId)
    {
        $this->albumId = $albumId;
    }

    /**
     * @see SORT_BY_* constants.
     */
    public function setSortBy(string $sortBy): ListAlbumContentRequest
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @see SORT_DIRECTION_* constants.
     */
    public function setSortDirection(string $sortDirection): ListAlbumContentRequest
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function setLimit(int $limit): ListAlbumContentRequest
    {
        $this->limit = $limit;
        return $this;
    }

    public function setStart(int $start): ListAlbumContentRequest
    {
        $this->start = $start;
        return $this;
    }

    public function getQueryParams(): ?array
    {
        return [
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'limit' => $this->limit,
            'start' => $this->start,
        ];
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->albumId,
        ];
    }

    public function getApiPath(): string
    {
        return 'album';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
