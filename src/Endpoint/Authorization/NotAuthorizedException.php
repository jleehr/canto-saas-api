<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Endpoint\Authorization;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class NotAuthorizedException extends Exception
{
    protected ?ResponseInterface $response;

    public function __construct($message = '', $code = 0, ?Throwable $previous = null, ?ResponseInterface $response = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
