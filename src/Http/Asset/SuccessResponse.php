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

final class SuccessResponse implements \Fairway\CantoSaasApi\Http\ResponseInterface
{
    private bool $success;

    public function __construct(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $this->success = $response->getBody()->getContents() === 'success';
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
