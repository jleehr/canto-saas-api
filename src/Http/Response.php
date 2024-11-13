<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http;

use function json_decode;

use JsonException;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

abstract class Response implements ResponseInterface
{
    /**
     * @throws InvalidResponseException
     */
    protected function parseResponse(PsrResponseInterface $response): array
    {
        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();

        if ($content === '') {
            throw new InvalidResponseException(
                'Empty response received',
                1626434956,
                null,
                $response
            );
        }

        try {
            $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidResponseException(
                'Invalid json response received',
                1626434988,
                $e,
                $response
            );
        }

        return $json;
    }
}
