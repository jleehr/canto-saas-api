<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Upload;

use Fairway\CantoSaasApi\Http\Request;

final class GetUploadSettingRequest extends Request
{
    private bool $refer;

    public function __construct(bool $refer = false)
    {
        $this->refer = $refer;
    }

    public function getQueryParams(): ?array
    {
        return [
            'refer' => $this->refer,
        ];
    }

    public function getApiPath(): string
    {
        return 'upload/setting';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
