<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Tests\Http\Asset;

use Fairway\CantoSaasApi\Http\Asset\BatchUpdatePropertiesRequest;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BatchUpdatePropertiesRequestTest extends TestCase
{
    #[Test]
    public function createRequestWithDefaultConfig(): void
    {
        $request = new BatchUpdatePropertiesRequest();

        self::assertSame('{"contents":[],"properties":[]}', $request->getBody());
        self::assertNull($request->getQueryParams());
        self::assertNull($request->getPathVariables());
    }

    #[Test]
    public function createRequestWithDefaultConfig123(): void
    {
        $request = new BatchUpdatePropertiesRequest();
        $request->addAsset('my-file', 'image');
        $request->addProperty('keyword', 'kword1,kword2');

        self::assertSame(
            '{"contents":[{"id":"my-file","scheme":"image"}],"properties":[{"propertyId":"keyword","propertyValue":"kword1,kword2","action":"","customField":false}]}',
            $request->getBody()
        );
    }
}
