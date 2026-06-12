<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Endpoint;

use Fairway\CantoSaasApi\Http\Asset\AddKeywordsRequest;
use Fairway\CantoSaasApi\Http\Asset\AddVersionCommentRequest;
use Fairway\CantoSaasApi\Http\Asset\AssignContentToAlbumRequest;
use Fairway\CantoSaasApi\Http\Asset\AttachKeywordToContentRequest;
use Fairway\CantoSaasApi\Http\Asset\AttachTagToContentRequest;
use Fairway\CantoSaasApi\Http\Asset\BatchGetContentDetailsRequest;
use Fairway\CantoSaasApi\Http\Asset\BatchGetContentDetailsResponse;
use Fairway\CantoSaasApi\Http\Asset\BatchUpdatePropertiesRequest;
use Fairway\CantoSaasApi\Http\Asset\BatchUpdatePropertiesResponse;
use Fairway\CantoSaasApi\Http\Asset\CreateShareLinksRequest;
use Fairway\CantoSaasApi\Http\Asset\CreateShareLinksResponse;
use Fairway\CantoSaasApi\Http\Asset\GetContentDetailsRequest;
use Fairway\CantoSaasApi\Http\Asset\GetContentDetailsResponse;
use Fairway\CantoSaasApi\Http\Asset\ListSpecificSchemeRequest;
use Fairway\CantoSaasApi\Http\Asset\RemoveContentFromAlbumRequest;
use Fairway\CantoSaasApi\Http\Asset\RemoveKeywordToContentRequest;
use Fairway\CantoSaasApi\Http\Asset\RemoveTagFromContentRequest;
use Fairway\CantoSaasApi\Http\Asset\RenameContentRequest;
use Fairway\CantoSaasApi\Http\Asset\SearchRequest;
use Fairway\CantoSaasApi\Http\Asset\SearchResponse;
use Fairway\CantoSaasApi\Http\Asset\SuccessResponse;
use Fairway\CantoSaasApi\Http\EmptyResponse;
use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\RequestInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

final class Asset extends AbstractEndpoint
{
    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function search(SearchRequest $request): SearchResponse
    {
        $response = $this->getResponse($request);
        return new SearchResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function listTheContentOfSpecifiedScheme(ListSpecificSchemeRequest $request): SearchResponse
    {
        $response = $this->getResponse($request);
        return new SearchResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchUpdateProperties(BatchUpdatePropertiesRequest $request): BatchUpdatePropertiesResponse
    {
        $response = $this->getResponse($request);
        return new BatchUpdatePropertiesResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchGetContentDetail(BatchGetContentDetailsRequest $request): BatchGetContentDetailsResponse
    {
        $response = $this->getResponse($request);
        return new BatchGetContentDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function getContentDetails(GetContentDetailsRequest $request): GetContentDetailsResponse
    {
        $response = $this->getResponse($request);
        return new GetContentDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function getAuthorizedUrlContent(string $uri, string $method = RequestInterface::GET): ResponseInterface
    {
        $httpRequest = new Request($method, $uri);
        return $this->getResponseWithHttpRequest($httpRequest);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function addVersionComment(AddVersionCommentRequest $request): SuccessResponse
    {
        $response = $this->getResponse($request);
        return new SuccessResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function renameContent(RenameContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function attachTagToContent(AttachTagToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeTagFromContent(RemoveTagFromContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function addKeywords(AddKeywordsRequest $request): SuccessResponse
    {
        $response = $this->getResponse($request);
        return new SuccessResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function attachKeywordToContent(AttachKeywordToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function assignContentToAlbum(AssignContentToAlbumRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeKeywordFromContent(RemoveKeywordToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchDeleteContent($request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeContentsFromAlbum(RemoveContentFromAlbumRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function downloadToPath(string $sourcePath, string $destinationPath): void
    {
        $fileContentReadStream = $this
            ->getAuthorizedUrlContent($sourcePath)
            ->getBody()
            ->detach();
        $tempFileWriteStream = fopen($destinationPath, 'wb');
        stream_copy_to_stream($fileContentReadStream, $tempFileWriteStream);
        fclose($tempFileWriteStream);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function createShareLinks(CreateShareLinksRequest $request): CreateShareLinksResponse
    {
        $response = $this->getResponse($request);
        return new CreateShareLinksResponse($response);
    }
}
