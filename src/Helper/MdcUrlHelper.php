<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Helper;

use Fairway\CantoSaasApi\Client;
use Fairway\CantoSaasApi\Endpoint\Authorization\NotAuthorizedException;
use Fairway\CantoSaasApi\Http\Asset\GetContentDetailsRequest;
use Fairway\CantoSaasApi\Http\InvalidResponseException;

final class MdcUrlHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $assetId
     * @param string $scheme
     * @return array{width: int, height: int, scale: float}
     * @throws NotAuthorizedException
     * @throws InvalidResponseException
     */
    public function scaleImageByMasterImageSize(string $assetId, string $scheme = 'image'): array
    {
        $request = new GetContentDetailsRequest($assetId, $scheme);
        $response = $this->client->asset()->getContentDetails($request);
        $width = (int)$response->getResponseData()['width'];
        $height = (int)$response->getResponseData()['height'];
        return $this->scaleWidthHeightByMasterImageSize($width, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return array{width: int, height: int, scale: float}
     */
    public function scaleWidthHeightByMasterImageSize(int $width, int $height): array
    {
        $masterImageSize = $this->client->getOptions()->getMasterImageSize();
        /** @var int|float $scale */
        $scale = min(1, $masterImageSize / $width, $masterImageSize / $height);
        return [
            'width' => (int)($scale * $width),
            'height' => (int)($scale * $height),
            'scale' => $scale,
        ];
    }
}
