<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Asset;

use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

final class CreateShareLinksResponse extends Response
{
    private string $content;

    public function __construct(ResponseInterface $response)
    {
        $this->content = $response->getBody()->getContents();
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
