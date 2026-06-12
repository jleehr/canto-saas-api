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

class GetContentDetailsRequest extends Request
{
    protected string $scheme;

    protected string $contentId;

    public function __construct(string $contentId, string $scheme)
    {
        $this->contentId = $contentId;
        $this->scheme = $scheme;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->scheme,
            $this->contentId,
        ];
    }

    public function getApiPath(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
