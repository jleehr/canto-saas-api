<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http;

use Fairway\CantoSaasApi\Client;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Psr7\Uri;
use JsonException;
use JsonSerializable;

abstract class Request implements RequestInterface, JsonSerializable
{
    public function getQueryParams(): ?array
    {
        return null;
    }

    public function getPathVariables(): ?array
    {
        return null;
    }

    public function jsonSerialize(): array
    {
        throw new \Exception('Serializing object not implemented.');
    }

    protected function hasBody(): bool
    {
        return false;
    }

    /**
     * @throws InvalidRequestException
     */
    public function getBody(): string
    {
        try {
            return json_encode($this, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidRequestException(
                'Can not generate json http body.',
                1626885024,
                $e
            );
        }
    }

    protected function buildRequestUrl(Client $client): Uri
    {
        $url = $client->getApiUrl($this->getApiPath());

        $pathVariables = $this->getPathVariables();
        $queryParams = $this->getQueryParams();
        if (is_array($pathVariables) === true) {
            $url = rtrim($url, '/');
            // Encode each segment so untrusted values cannot alter the
            // request path (e.g. via "../", "?" or "#").
            $url .= '/' . implode('/', array_map(
                static fn ($segment): string => rawurlencode((string)$segment),
                $pathVariables
            ));
        }
        if (is_array($queryParams) && count($queryParams) > 0) {
            $url .= '?' . http_build_query($queryParams);
        }

        return new Uri($url);
    }

    /**
     * @throws InvalidRequestException
     */
    public function toHttpRequest(Client $client, array $withHeaders = []): HttpRequest
    {
        $uri = $this->buildRequestUrl($client);
        if ($this->hasBody()) {
            $withHeaders['Content-Type'] = 'application/json';
        }
        return new HttpRequest(
            $this->getMethod(),
            $uri,
            $withHeaders,
            $this->hasBody() ? $this->getBody() : null,
        );
    }
}
