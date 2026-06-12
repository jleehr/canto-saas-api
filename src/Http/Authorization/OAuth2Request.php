<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Http\Authorization;

use Fairway\CantoSaasApi\Http\Request;

class OAuth2Request extends Request
{
    public const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
    public const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
    public const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';
    public const SCOPE_ADMIN = 'admin';
    public const SCOPE_CONTRIBUTOR = 'contributor';
    public const SCOPE_CONSUMER = 'consumer';

    protected string $appId = '';

    protected string $appSecret = '';

    protected string $grantType = self::GRANT_TYPE_CLIENT_CREDENTIALS;

    protected string $redirectUri = '';

    protected string $code = '';

    protected string $refreshToken = '';

    protected string $scope = self::SCOPE_ADMIN;

    protected string $userId = '';

    public function setAppId(string $appId): OAuth2Request
    {
        $this->appId = $appId;
        return $this;
    }

    public function setAppSecret(string $appSecret): OAuth2Request
    {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * @see GRANT_TYPE_* constants.
     */
    public function setGrantType(string $grantType): OAuth2Request
    {
        $this->grantType = $grantType;
        return $this;
    }

    public function setRedirectUri(string $redirectUri): OAuth2Request
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    public function setCode(string $code): OAuth2Request
    {
        $this->code = $code;
        return $this;
    }

    public function setRefreshToken(string $refreshToken): OAuth2Request
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @see SCOPE_* constants.
     */
    public function setScope(string $scope): OAuth2Request
    {
        $this->scope = $scope;
        return $this;
    }

    public function setUserId(string $userId): OAuth2Request
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * The OAuth parameters are sent in the form-encoded POST body, not as
     * query parameters, so the credentials never appear in the request URL
     * (and thus in access logs, proxy logs or APM traces). getQueryParams()
     * intentionally returns null (parent default).
     */
    public function getFormParams(): array
    {
        return [
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'grant_type' => $this->grantType,
            'redirect_uri' => $this->redirectUri,
            'code' => $this->code,
            'refresh_token' => $this->refreshToken,
            'scope' => $this->scope,
            'user_id' => $this->userId,
        ];
    }

    public function getApiPath(): string
    {
        return 'token';
    }

    public function getMethod(): string
    {
        return self::POST;
    }
}
