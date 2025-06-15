<?php

namespace App\Services\Reaction;

use App\Enums\ReactionStatusEnum;
use App\Models\Article;
use App\Enums\ReactionTypeEnum;
use App\Models\Reaction;
use App\Models\Replay;
use App\Services\Base\ContextService;

/**
 * Class ReactionService.
 */
class ReactionService
{
    protected $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function like($article_id)
    {
        $article = Article::findByIdOrFail($article_id);

        $likeExist = Reaction::where('article_id' , $article_id)
                        ->where('user_id' , auth()->id())
                        ->where('type' , ReactionTypeEnum::LIKE)
                        ->exists();
        
        if(! $likeExist)
        {
            $article->reactions()->create([
                'type' => ReactionTypeEnum::LIKE,
                'user_id' => auth()->id(),
            ]);

            $this->updateArticleCounters($article , '+' , 'likes');

            $article->save();
        }
    }

    public function unLike($article_id)
    {
        $reaction = Reaction::where('article_id' , $article_id)
                        ->where('user_id' , auth()->id())
                        ->where('type' , ReactionTypeEnum::LIKE)
                        ->first();
        
        $reaction->delete();

        $article = Article::findByIdOrFail($article_id);

        $this->updateArticleCounters($article , '-' , 'likes');

        $article->save();
    }

    public function comment($article_id , $data)
    {
        $article = Article::findByIdOrFail($article_id);

        $article->reactions()->create([
            'type' => ReactionTypeEnum::COMMENT,
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
        ]);

        $this->updateArticleCounters($article , '+' , 'comments');

        $article->save();
    }

    public function unComment($comment_id)
    {
        $comment = Reaction::findByIdOrFail($comment_id);

        if($comment->type == ReactionTypeEnum::COMMENT->value)
            $comment->status = ReactionStatusEnum::DELETED;

        $comment->save();

        $article = Article::findByIdOrFail($comment->article_id);

        $this->updateArticleCounters($article , '-' , 'comments');

        // $this->updateReplays($article , $comment , '-');
    }

    public function getLikes($article_id , $data)
    {
        return $this->getAllReactions($article_id , ReactionTypeEnum::LIKE->value);
    }

    public function getComments($article_id , $data)
    {
        return $this->getReactions($article_id , ReactionTypeEnum::COMMENT->value);
    }

    public function getAllComments($article_id , $data)
    {
        return $this->getAllReactions($article_id , ReactionTypeEnum::COMMENT->value);
    }

    private function getReactions($article_id , $type)
    {
        
        return $this->getAllReactions($article_id , $type )
                    ->where('status' , ReactionStatusEnum::EXIST->value);
    }

    private function getAllReactions($article_id , $type )
    {
        return Reaction::where('article_id' , $article_id)
                        ->where('type' , $type)
                        ->with('user')->get();
    }

    private function updateArticleCounters($article , $operation , $type)
    {
        $operation = ($operation == '+' ? 'increment' : 'decrement');

        $article->$operation('number_of_' . $type);

        $article->save();
    }
    
    // private function updateReplays($article , $comment , $operation)
    // {
    //     if($operation == '-')
    //     {
    //         $replay = Replay::where('reaction_id' , $comment->id)
    //                 ->where('status' , ReactionStatusEnum::EXIST)
    //                 ->first();

    //         $replay->status = ReactionStatusEnum::DELETED;

    //         $replay->save();
    //     }

    //     $this->updateArticleCounters($article , $operation , 'comments');
    // }
}
