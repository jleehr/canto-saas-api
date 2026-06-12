<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Endpoint;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Endpoint\Authorization\NotAuthorizedException;
use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\RequestInterface as CantoRequestInterface;
use Fairway\CantoSaasApi\Http\UploadRequest;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * @internal
 */
abstract class AbstractEndpoint
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @throws NotAuthorizedException
     * @throws RuntimeException
     */
    protected function sendRequest(RequestInterface $request, array $options = [], bool $withAccessToken = true): ResponseInterface
    {
        if ($withAccessToken) {
            $accessToken = $this->client->getAccessToken();
            if ($accessToken !== null) {
                $request = $request->withHeader(
                    'Authorization',
                    'Bearer ' . $accessToken
                );
                assert($request instanceof RequestInterface);
            }
        }

        try {
            $response = $this->client->getHttpClient()->send($request, $options);
        } catch (RuntimeException $e) {
            /*
             * API seems to respond with 404 when no authentication token is given but needed.
             * When token is given but invalid, API responds with 401.
             */
            if ($withAccessToken && (($accessToken === null && $e->getCode() === 404) || $e->getCode() === 401)) {
                throw new NotAuthorizedException(
                    'Not authorized',
                    1626717511,
                );
            }
            throw $e;
        }

        return $response;
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    protected function getResponse(CantoRequestInterface $request): ResponseInterface
    {
        $httpRequest = $request->toHttpRequest($this->getClient());
        return $this->getResponseWithHttpRequest($httpRequest);
    }

    /**
     * @throws InvalidResponseException|NotAuthorizedException|\JsonException
     */
    protected function getMultipartResponse(UploadRequest $request): ResponseInterface
    {
        $httpRequest = $request->toHttpRequest($this->getClient());
        return $this->getResponseWithHttpRequest($httpRequest, [
            'multipart' => $request->getMultipart(),
        ], false);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    protected function getResponseWithHttpRequest(RequestInterface $request, array $options = [], bool $withAccessToken = true): ResponseInterface
    {
        try {
            return $this->sendRequest($request, $options, $withAccessToken);
        } catch (GuzzleException $e) {
            throw new InvalidResponseException(
                sprintf(
                    'Invalid http status code received. Expected 200, got %s.',
                    $e->getCode()
                ),
                1627649307,
                $e
            );
        }
    }
}
