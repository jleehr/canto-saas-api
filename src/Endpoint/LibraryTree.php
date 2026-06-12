<?php

declare(strict_types=1);

/*
 * This file is part of the Canto Saas Api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fairway\CantoSaasApi\Endpoint;

use Fairway\CantoSaasApi\Endpoint\Authorization\NotAuthorizedException;
use Fairway\CantoSaasApi\Http\InvalidResponseException;
use Fairway\CantoSaasApi\Http\LibraryTree\CreateAlbumFolderRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\CreateAlbumFolderResponse;
use Fairway\CantoSaasApi\Http\LibraryTree\DeleteAlbumFolderResponse;
use Fairway\CantoSaasApi\Http\LibraryTree\DeleteFolderOrAlbumRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\GetDetailsRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\GetDetailsResponse;
use Fairway\CantoSaasApi\Http\LibraryTree\GetMyCollectionDetailInfoRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\GetTreeResponse;
use Fairway\CantoSaasApi\Http\LibraryTree\ListAlbumContentRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\ListAlbumContentResponse;
use Fairway\CantoSaasApi\Http\LibraryTree\SearchFolderRequest;
use Fairway\CantoSaasApi\Http\LibraryTree\SearchFolderResponse;

final class LibraryTree extends AbstractEndpoint
{
    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function searchFolderContent(SearchFolderRequest $request): SearchFolderResponse
    {
        $response = $this->getResponse($request);
        return new SearchFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function listAlbumContent(ListAlbumContentRequest $request): ListAlbumContentResponse
    {
        $response = $this->getResponse($request);
        return new ListAlbumContentResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getTree(GetTreeRequest $request): GetTreeResponse
    {
        $response = $this->getResponse($request);
        return new GetTreeResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getDetails(GetDetailsRequest $request): GetDetailsResponse
    {
        $response = $this->getResponse($request);
        return new GetDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function createFolder(CreateAlbumFolderRequest $request): CreateAlbumFolderResponse
    {
        $response = $this->getResponse($request);
        return new CreateAlbumFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getMyCollectionDetailInfo(GetMyCollectionDetailInfoRequest $request): SearchFolderResponse
    {
        $response = $this->getResponse($request);
        return new SearchFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function createAlbum(CreateAlbumFolderRequest $request): CreateAlbumFolderResponse
    {
        $request->setType(CreateAlbumFolderRequest::ALBUM);
        $response = $this->getResponse($request);
        return new CreateAlbumFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function deleteFolderOrAlbum(DeleteFolderOrAlbumRequest $request): DeleteAlbumFolderResponse
    {
        return new DeleteAlbumFolderResponse($this->getResponse($request));
    }
}
