<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Authorization;

use Fairway\CantoSaasApi\Http\Authorization\OAuth2Response;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class OAuth2ResponseTest extends TestCase
{
    #[Test]
    public function createValidResponse(): void
    {
        $response = new OAuth2Response(
            new Response(
                200,
                [],
                '{"accessToken":"access-token-1234","expiresIn":"3600","tokenType":"Bearer","refreshToken":"refresh-token-1234"}'
            )
        );

        self::assertSame('access-token-1234', $response->getAccessToken());
        self::assertSame(3600, $response->getExpiresIn());
        self::assertSame('Bearer', $response->getTokenType());
        self::assertSame('refresh-token-1234', $response->getRefreshToken());
    }

    #[Test]
    public function throwExceptionWithEmptyBody(): void
    {
        self::expectExceptionCode(1626449779);

        new OAuth2Response(new Response(200, [], ''));
    }

    #[Test]
    public function throwExceptionWithInvalidJsonBody(): void
    {
        self::expectExceptionCode(1626449779);

        new OAuth2Response(new Response(200, [], 'invalid-json'));
    }
}
