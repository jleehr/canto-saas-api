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

class BatchGetContentDetailsRequest extends Request
{
    /** @var array<array{id: string, scheme: string}>|GetContentDetailsRequest[] */
    protected array $listContentDetails;

    public function __construct(array $listContentDetails)
    {
        $this->listContentDetails = $listContentDetails;
    }

    public function jsonSerialize(): array
    {
        $data = [];
        foreach ($this->listContentDetails as $item) {
            if ($item instanceof GetContentDetailsRequest) {
                $data[] = $item->getPathVariables();
            } else {
                $data[] = $item;
            }
        }
        return array_filter($data);
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function getApiPath(): string
    {
        return 'batch/content';
    }

    public function getMethod(): string
    {
        return self::POST;
    }
}
