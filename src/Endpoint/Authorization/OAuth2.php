<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Endpoint\Authorization;

use Fairway\CantoSaasApi\Endpoint\AbstractEndpoint;
use Fairway\CantoSaasApi\Http\Authorization\OAuth2Request;
use Fairway\CantoSaasApi\Http\Authorization\OAuth2Response;
use Fairway\CantoSaasApi\Http\RequestInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;

final class OAuth2 extends AbstractEndpoint
{
    /**
     * @throws AuthorizationFailedException
     * @throws NotAuthorizedException
     */
    public function obtainAccessToken(OAuth2Request $request): OAuth2Response
    {
        // The credentials are sent in the form-encoded POST body instead of
        // the query string (RFC 6749 section 2.3.1), so they cannot end up in
        // access logs, proxy logs or APM traces.
        $uri = $this->buildRequestUrl($request);
        $httpRequest = new Request(
            $request->getMethod(),
            $uri,
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            http_build_query($request->getFormParams())
        );

        try {
            $response = $this->sendRequest($httpRequest);
        } catch (GuzzleException $e) {
            throw new AuthorizationFailedException(
                $this->sanitizeExceptionMessage($e->getMessage()),
                1626447895,
                $e
            );
        }

        return new OAuth2Response($response);
    }

    /**
     * Defense in depth: even though the credentials are sent in the request
     * body, mask them in case they show up in an exception message (e.g. via
     * a response summary), so they cannot leak into logs or error trackers
     * of the consuming application.
     */
    private function sanitizeExceptionMessage(string $message): string
    {
        // Case-insensitive, covering the url-encoded "=" (%3D) as well as
        // JSON-style contexts ("app_secret":"..."), so the masking also
        // catches encoded, differently cased or echoed variants. It may mask
        // slightly too much (e.g. an url-encoded "&"), which is the safer
        // failure mode than leaking a credential.
        $message = preg_replace(
            '/(app_secret|refresh_token|code)(=|%3D)[^&\s"\']+/i',
            '$1$2***',
            $message
        ) ?? $message;
        return preg_replace(
            '/"(app_secret|refresh_token|code)"\s*:\s*"[^"]*"/i',
            '"$1":"***"',
            $message
        ) ?? $message;
    }

    protected function buildRequestUrl(RequestInterface $request): Uri
    {
        return new Uri(sprintf(
            'https://oauth.%s/oauth/api/oauth2/%s',
            $this->getClient()->getOptions()->getCantoDomain(),
            urlencode(trim($request->getApiPath(), '/'))
        ));
    }
}
