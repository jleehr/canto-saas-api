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

final class CreateAlbumFolderResponse extends Response
{
    private array $responseData;
    private string $id;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->responseData = $this->parseResponse($response);
        $this->id = $this->responseData['id'];
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
