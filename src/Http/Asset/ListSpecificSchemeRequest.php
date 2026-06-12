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

/**
 * This Request is very similar to the SearchRequest
 * @see SearchRequest
 */
final class ListSpecificSchemeRequest extends Request
{
    private string $scheme;
    private string $keyword = '';
    private array $tags = [];
    private string $tagsLiteral;
    private array $keywords = [];
    private array $approval = [];
    private array $owner = [];
    private string $fileSize = '';
    private string $created = '';
    private string $createdTime = '';
    private string $uploadedTime = '';
    private string $lastModified = '';
    private string $dimension = '';
    private string $resolution = '';
    private array $orientation = [];
    private string $duration = '';
    private string $pageNumber = '';
    private array $storageClass = [];
    private string $searchInField = '';
    private string $operator = 'and';
    private bool $exactMatch = false;
    private string $sortBy = 'time';
    private string $sortDirection = 'ascending';
    private int $start = 0;
    private int $end = 100;
    private array $custom = [];

    public function __construct(string $scheme)
    {
        $this->scheme = $scheme;
    }

    public function andTag(string $tag): self
    {
        $this->tags[] = ['+', $tag];
        return $this;
    }

    public function orTag(string $tag): self
    {
        $this->tags[] = ['|', $tag];
        return $this;
    }

    public function andKeyword(string $keyword): self
    {
        $this->tags[] = ['+', $keyword];
        return $this;
    }

    public function orKeyword(string $keyword): self
    {
        $this->keywords[] = ['|', $keyword];
        return $this;
    }

    public function setKeyword(string $keyword): self
    {
        $this->keyword = $keyword;
        return $this;
    }

    public function setTagsLiteral(string $tagsLiteral): self
    {
        $this->tagsLiteral = $tagsLiteral;
        return $this;
    }

    public function setApproval(string $approval, string ...$or): self
    {
        $list = [$approval, ...$or];
        if (in_array('expired', $list, true)) {
            $list = ['expired'];
        }
        $this->approval = [];
        foreach ($list as $item) {
            $this->approval[] = ['|', $item];
        }
        return $this;
    }

    public function setOwner(string $owner, string ...$or): self
    {
        $list = [$owner, ...$or];
        $this->owner = [];
        foreach ($list as $item) {
            $this->owner[] = ['|', $item];
        }
        return $this;
    }

    public function setOrientation(string $orientation, string ...$or): self
    {
        $list = [$orientation, ...$or];
        $this->orientation = [];
        foreach ($list as $item) {
            $this->orientation[] = ['|', $item];
        }
        return $this;
    }

    public function setStorageClass(string $storageClass, string ...$or): self
    {
        $list = [$storageClass, ...$or];
        $this->storageClass = [];
        foreach ($list as $item) {
            $this->storageClass[] = ['|', $item];
        }
        return $this;
    }

    public function setFileSize(int $min, int $max): self
    {
        $this->fileSize = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setCreated(int $min, int $max): self
    {
        $this->created = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setCreatedTime(int $min, int $max): self
    {
        $this->createdTime = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setUploadedTime(int $min, int $max): self
    {
        $this->uploadedTime = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setLastModified(int $min, int $max): self
    {
        $this->lastModified = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setDimension(int $min, int $max): self
    {
        $this->dimension = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setResolution(int $min, int $max): self
    {
        $this->resolution = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setDuration(int $min, int $max): self
    {
        $this->duration = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setPageNumber(int $min, int $max): self
    {
        $this->pageNumber = sprintf('%d..%d', $min, $max);
        return $this;
    }

    public function setOperator(string $operator): self
    {
        $this->operator = $operator;
        return $this;
    }

    public function setExactMatch(bool $exactMatch): self
    {
        $this->exactMatch = $exactMatch;
        return $this;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function setEnd(int $end): self
    {
        $this->end = $end;
        return $this;
    }

    public function setSearchInField(string $searchInField): self
    {
        $this->searchInField = $searchInField;
        return $this;
    }

    public function setSortBy(string $sortBy): self
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    public function setSortDirection(bool $ascending): self
    {
        $this->sortDirection = $ascending ? 'ascending' : 'descending';
        return $this;
    }

    public function addCustomField(string $key, string $value): self
    {
        $this->custom[$key] = $value;
        return $this;
    }

    public function getQueryParams(): ?array
    {
        $queryParams = [];
        if ($this->keyword) {
            $queryParams['keyword'] = $this->keyword;
        }
        if ($this->tagsLiteral) {
            $queryParams['tagsLiteral'] = $this->tagsLiteral;
        }
        if ($this->tags) {
            $queryParams['tags'] = $this->implodeMultiValueArray($this->tags);
        }
        if ($this->keywords) {
            $queryParams['keywords'] = $this->implodeMultiValueArray($this->keywords);
        }
        if ($this->approval) {
            $queryParams['approval'] = $this->implodeMultiValueArray($this->approval);
        }
        if ($this->owner) {
            $queryParams['owner'] = $this->implodeMultiValueArray($this->owner);
        }
        if ($this->orientation) {
            $queryParams['orientation'] = $this->implodeMultiValueArray($this->orientation);
        }
        if ($this->storageClass) {
            $queryParams['storageClass'] = $this->implodeMultiValueArray($this->storageClass);
        }
        if ($this->fileSize) {
            $queryParams['fileSize'] = $this->fileSize;
        }
        if ($this->created) {
            $queryParams['created'] = $this->created;
        }
        if ($this->createdTime) {
            $queryParams['createdTime'] = $this->createdTime;
        }
        if ($this->uploadedTime) {
            $queryParams['uploadedTime'] = $this->uploadedTime;
        }
        if ($this->lastModified) {
            $queryParams['lastModified'] = $this->lastModified;
        }
        if ($this->dimension) {
            $queryParams['dimension'] = $this->dimension;
        }
        if ($this->resolution) {
            $queryParams['resolution'] = $this->resolution;
        }
        if ($this->duration) {
            $queryParams['duration'] = $this->duration;
        }
        if ($this->pageNumber) {
            $queryParams['pageNumber'] = $this->pageNumber;
        }
        if ($this->searchInField) {
            $queryParams['searchInField'] = $this->searchInField;
        }
        if ($this->operator) {
            $queryParams['operator'] = $this->operator;
        }
        $queryParams['exactMatch'] = $this->exactMatch ? 'true' : 'false';
        if ($this->sortBy) {
            $queryParams['sortBy'] = $this->sortBy;
        }
        if ($this->sortDirection) {
            $queryParams['sortDirection'] = $this->sortDirection;
        }
        if ($this->start) {
            $queryParams['start'] = $this->start;
        }
        if ($this->end) {
            $queryParams['end'] = $this->end;
        }
        if ($this->custom) {
            foreach ($this->custom as $key => $value) {
                $queryParams[$key] = $value;
            }
        }
        return $queryParams;
    }

    private function implodeMultiValueArray(array $multiValue): string
    {
        if (count($multiValue) === 1) {
            return $multiValue[0][1] ?? '';
        }
        $values = [];
        foreach ($multiValue as $item) {
            $values[] = implode('', $item);
        }
        return implode('', $values);
    }

    public function getApiPath(): string
    {
        return $this->scheme;
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
