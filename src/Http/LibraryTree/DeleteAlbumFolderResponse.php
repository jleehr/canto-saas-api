<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\LibraryTree;

use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

final class DeleteAlbumFolderResponse extends Response
{
    private array $permissionFail;
    private array $deleteFail;
    private string $status;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseData = $this->parseResponse($response);
        $this->deleteFail = $responseData['deleteFail'] ?? [];
        $this->permissionFail = $responseData['permissionFail'] ?? [];
        $this->status = $responseData['status'];
    }

    /**
     * A list of ids, which failed to delete, because of missing permissions.
     * @return string[]
     */
    public function getPermissionFail(): array
    {
        return $this->permissionFail;
    }

    /**
     * A list of ids, which failed to delete, because of an error during deletion.
     * @return string[]
     */
    public function getDeleteFail(): array
    {
        return $this->deleteFail;
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
