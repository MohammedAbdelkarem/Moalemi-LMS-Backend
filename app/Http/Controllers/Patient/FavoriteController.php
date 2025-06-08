<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResouce;
use App\Services\Favorite\FavoriteService;
use App\Http\Resources\Article\ArticleResource;

class FavoriteController extends Controller
{
    public function __construct(
        protected FavoriteService $favoriteService
    ){}

    public function get(Request $request)
    {
        return success(
            $this->favoriteService->getByType($request->all()),
            ApiMessages::MSG_SUCCESS,
            $request->type == 'doctor'
            ? DoctorResouce::class
            : ArticleResource::class,
            $request->has('per_page')  
        );
    }

    public function setDoctorAsFavorite($id)
    {
        return success(
            $this->favoriteService->setAsFavorite($id , 'doctor'),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function setArticleAsFavorite($id)
    {
        return success(
            $this->favoriteService->setAsFavorite($id , 'article'),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function setDoctorAsUnFavorite($id)
    {
        return success(
            $this->favoriteService->setAsUnFavorite($id , 'doctor'),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function setArticleAsUnFavorite($id)
    {
        return success(
            $this->favoriteService->setAsUnFavorite($id , 'article'),
            ApiMessages::MSG_SUCCESS
        );
    }
}
