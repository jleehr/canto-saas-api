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

final class QueryUploadStatusRequest extends Request
{
    private int $hours;

    /**
     * @param int $hours An integer of 1..24.
     */
    public function __construct(int $hours = 1)
    {
        $this->hours = $hours;
    }

    public function getQueryParams(): ?array
    {
        return [
            'hours' => $this->hours,
        ];
    }

    public function getApiPath(): string
    {
        return 'upload/status';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
