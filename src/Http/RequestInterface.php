<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http;

use Fairway\CantoSaasApi\Client;

interface RequestInterface
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const PATCH = 'PATCH';

    /**
     * @return array<string|int, scalar>|null Null if no query params exists
     */
    public function getQueryParams(): ?array;

    /**
     * @return array<string|int, scalar>|null Null if no path variable exists.
     */
    public function getPathVariables(): ?array;

    /**
     * The API-Path for the specific request
     * @return string
     */
    public function getApiPath(): string;

    /**
     * Request type e.g GET, POST,…
     * @return string
     */
    public function getMethod(): string;

    /**
     * Transform Canto Request to Psr7-Request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function toHttpRequest(Client $client, array $withHeaders = []): \Psr\Http\Message\RequestInterface;
}
