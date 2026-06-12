<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Asset;

use Fairway\CantoSaasApi\Http\Asset\BatchUpdatePropertiesResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BatchUpdatePropertiesResponseTest extends TestCase
{
    #[Test]
    public function createValidResponse(): void
    {
        $httpResponse = new Response(200, [], 'success');
        $response = new BatchUpdatePropertiesResponse($httpResponse);

        self::assertSame('success', $response->getBody());
    }
}
