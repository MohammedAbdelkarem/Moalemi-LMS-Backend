<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetItemsRequest;
use App\Services\Article\ArticleService;
use App\Http\Resources\Article\ArticleResource;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService
    ){}

    public function list(GetItemsRequest $request)
    {
        return success(
            $this->articleService->getAll($request->validated()),    
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class,
            $request->has('per_page')
        );
    }

    public function show($id)
    {
        return success(
            $this->articleService->show($id),
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class,
        );
    }

    public function search(Request $request)
    {
        return success(
            $this->articleService->search($request->all()),
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class,
            $request->has('per_page')
        );
    }
}
