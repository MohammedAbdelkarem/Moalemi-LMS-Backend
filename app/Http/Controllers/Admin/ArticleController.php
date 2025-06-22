<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Article\ArticleService;
use App\Http\Resources\Article\ArticleResource;
use App\Services\Reaction\ReactionService;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService,
        protected ReactionService $reactionService,
    ) {}

    public function filter(Request $request)
    {
        return success(
            $this->articleService->filterArticles($request->all()),
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class,
            $request->has('per_page')
        );
    }
    public function show($id)
    {
        return success(
            $this->articleService->showForAdmin($id),
            ApiMessages::MSG_SUCCESS,
            ArticleResource::class
        );
    }
    
    public function destroy($id)
    {
        return success(
            $this->articleService->destroy($id),
            ApiMessages::MSG_SUCCESS
        );
    }
}
