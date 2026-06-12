<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

class GetTreeResponse extends Response
{
    protected array $results;

    protected string $sortBy;

    protected string $sortDirection;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseData = $this->parseResponse($response);

        $this->results = $responseData['results'] ?? [];
        $this->sortBy = $responseData['sortBy'];
        $this->sortDirection = $responseData['sortDirection'];
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }
}
