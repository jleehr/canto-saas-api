<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Fixture;

use Fairway\CantoSaasApi\Http\Request;

/**
 * Test double that exposes both path sources of the request URL, so the
 * central path guard can be tested without relying on a concrete request.
 */
final class ConfigurableRequest extends Request
{
    /**
     * @param array<string|int, scalar>|null $pathVariables
     * @param array<string|int, scalar>|null $queryParams
     */
    public function __construct(
        private readonly string $apiPath = '',
        private readonly ?array $pathVariables = null,
        private readonly ?array $queryParams = null
    ) {
    }

    public function getApiPath(): string
    {
        return $this->apiPath;
    }

    public function getPathVariables(): ?array
    {
        return $this->pathVariables;
    }

    public function getQueryParams(): ?array
    {
        return $this->queryParams;
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
