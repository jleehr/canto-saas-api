<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

final class BatchGetContentDetailsResponse extends Response
{
    private array $assets;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseData = $this->parseResponse($response);
        $this->assets = $responseData['docResult'] ?? [];
    }

    public function getAssets(): array
    {
        return $this->assets;
    }
}
