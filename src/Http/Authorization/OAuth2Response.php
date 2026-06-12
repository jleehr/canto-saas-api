<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Authorization;

use Fairway\CantoSaasApi\Endpoint\Authorization\AuthorizationFailedException;
use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\Response;
use Psr\Http\Message\ResponseInterface;

class OAuth2Response extends Response
{
    private string $accessToken;

    private int $expiresIn;

    private string $tokenType;

    private ?string $refreshToken;

    /**
     * @throws AuthorizationFailedException
     */
    public function __construct(ResponseInterface $response)
    {
        try {
            $json = $this->parseResponse($response);
        } catch (InvalidResponseException $e) {
            throw new AuthorizationFailedException(
                'Authorization failed - ' . $e->getMessage(),
                1626449779,
                $e
            );
        }

        $this->accessToken = $json['accessToken'] ?? '';
        $this->expiresIn = (int)($json['expiresIn'] ?? 0);
        $this->tokenType = $json['tokenType'] ?? '';
        $this->refreshToken = $json['refreshToken'] ?? null;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
