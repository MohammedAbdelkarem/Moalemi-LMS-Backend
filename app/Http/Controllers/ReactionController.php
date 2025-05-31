<?php

namespace App\Http\Controllers;

use App\Constants\ApiMessages;
use App\Http\Requests\Article\CommentRequest;
use App\Http\Resources\Reaction\ReactionResource;
use App\Services\Reaction\ReactionService;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function __construct(
        protected ReactionService $reactionService
    ) {}

    public function like($article_id)
    {
        return success(
            $this->reactionService->like($article_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function unLike($article_id)
    {
        return success(
            $this->reactionService->unLike($article_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function comment($article_id , CommentRequest $request)
    {
        return success(
            $this->reactionService->comment($article_id , $request->all()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function unComment($comment_id)
    {
        return success(
            $this->reactionService->unComment($comment_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getLikes($article_id , Request $request)
    {
        return success(
            $this->reactionService->getLikes($article_id , $request->all()),
            ApiMessages::MSG_SUCCESS,
            ReactionResource::class,
            $request->has('per_page')
        );
    }

    public function getCommentsForUser($article_id , Request $request)
    {
        return success(
            $this->reactionService->getComments($article_id , $request->all()),
            ApiMessages::MSG_SUCCESS,
            ReactionResource::class,
            $request->has('per_page')
        );
    }
    
    public function getCommentsForAdmin($article_id , Request $request)
    {
        return success(
            $this->reactionService->getAllComments($article_id , $request->all()),
            ApiMessages::MSG_SUCCESS,
            ReactionResource::class,
            $request->has('per_page')
        );
    }
}
