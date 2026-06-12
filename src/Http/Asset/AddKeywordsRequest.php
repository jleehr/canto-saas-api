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

final class AddKeywordsRequest extends Request
{
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    protected function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function getApiPath(): string
    {
        return 'keyword';
    }

    public function getMethod(): string
    {
        return self::POST;
    }
}
