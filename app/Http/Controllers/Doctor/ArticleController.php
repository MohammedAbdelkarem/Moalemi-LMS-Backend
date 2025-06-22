<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\CreateArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Requests\GetItemsRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Services\Article\ArticleService;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        
    ) {
        $this->middleware('user.banned')->only(['store', 'update' , 'destroy']);
    }

    // public function index(GetItemsRequest $request)
    // {
    //     return success(
    //         $this->categoryService->getAll( $request->validated()),
    //         ApiMessages::MSG_SUCCESS,
    //         CategoryResource::class,
    //         $request->has('per_page')
    //     );
    // }

    public function show($id)
    {
        return success(
            $this->articleService->show($id),
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class
        );
    }

    public function store(CreateArticleRequest $request)
    {
        return createdSuccess(
            $this->articleService->store($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function update(UpdateArticleRequest $request , $id)
    {
        return success(
            $this->articleService->update($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function destroy($id)
    {
        return success(
            $this->articleService->destroy($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getMyArticles(GetItemsRequest $request)
    {
        return success(
            $this->articleService->getMyArticles($request->validated()),    
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class,
            $request->has('per_page')
        );
    }
}
