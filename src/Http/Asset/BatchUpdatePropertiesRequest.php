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

class BatchUpdatePropertiesRequest extends Request
{
    protected array $assets = [];

    protected array $properties = [];

    public function addAsset(string $id, string $scheme): BatchUpdatePropertiesRequest
    {
        $this->assets[] = [
            'id' => $id,
            'scheme' => $scheme,
        ];
        return $this;
    }

    public function addProperty(string $id, string $value, string $action = '', bool $customField = false): BatchUpdatePropertiesRequest
    {
        $this->properties[] = [
            'propertyId' => $id,
            'propertyValue' => $value,
            'action' => $action,
            'customField' => $customField,
        ];
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'contents' => $this->assets,
            'properties' => $this->properties,
        ];
    }

    public function getApiPath(): string
    {
        return 'batch/edit';
    }

    public function getMethod(): string
    {
        return self::PUT;
    }

    protected function hasBody(): bool
    {
        return true;
    }
}
