<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

use Psr\Http\Message\ResponseInterface;

class BatchUpdatePropertiesResponse implements \Fairway\CantoSaasApi\Http\ResponseInterface
{
    protected string $body;

    public function __construct(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $this->body = $response->getBody()->getContents();
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isSuccessful(): bool
    {
        return $this->body === 'success';
    }
}
