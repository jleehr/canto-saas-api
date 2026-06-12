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

final class GetMyCollectionDetailInfoRequest extends Request
{
    private string $collectionId;
    private string $orderBy = '';
    private string $sortDirection = '';

    public function __construct(string $collectionId)
    {
        $this->collectionId = $collectionId;
    }

    public function setOrderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setOrderDirection(string $orderDirection): self
    {
        $this->sortDirection = $orderDirection;
        return $this;
    }

    public function getQueryParams(): ?array
    {
        if ($this->sortDirection || $this->orderBy) {
            return [
                'sortDirection' => $this->sortDirection,
                'sortBy' => $this->orderBy,
            ];
        }
        return null;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->collectionId,
        ];
    }

    public function getApiPath(): string
    {
        return 'mycollections';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
