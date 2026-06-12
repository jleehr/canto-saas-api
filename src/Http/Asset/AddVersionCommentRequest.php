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

class AddVersionCommentRequest extends Request
{
    private string $scheme;
    private string $id;
    private string $versionId;
    private string $comment;

    public function __construct(string $scheme, string $id, string $versionId, string $comment)
    {
        $this->scheme = $scheme;
        $this->id = $id;
        $this->versionId = $versionId;
        $this->comment = $comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'scheme' => $this->scheme,
            'id' => $this->id,
            'versionId' => $this->versionId,
            'comment' => $this->comment,
        ];
    }

    public function getApiPath(): string
    {
        return 'version/comment';
    }

    public function getMethod(): string
    {
        return self::POST;
    }

    protected function hasBody(): bool
    {
        return true;
    }
}
