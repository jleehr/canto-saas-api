<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Upload;

use Fairway\CantoSaasApi\DTO\Status;
use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

final class QueryUploadStatusResponse extends Response
{
    /**
     * @var array<array{name: string, time: string, status: string}>
     */
    private array $results;
    private int $found;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $result = $this->parseResponse($response);
        $this->found = (int)($result['found'] ?? 0);
        $this->results = $result['results'] ?? [];
    }

    /**
     * @return array<array{name: string, time: string, status: string}>
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return array<Status>
     */
    public function getStatusItems(): array
    {
        return array_map(static fn (array $item) => Status::fromResultItem($item), $this->results);
    }

    public function getFound(): int
    {
        return $this->found;
    }
}
