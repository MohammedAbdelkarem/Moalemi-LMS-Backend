<?php

namespace App\Services\Reaction;

use App\Models\Article;
use App\Enums\ReactionTypeEnum;
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
    public function like($article_id , $reactionable_id)
    {
        
        $article = Article::findByIdOrFail($article_id);
        $article->reactions()->create([
            'type' => ReactionTypeEnum::LIKE,
            'reactionable_id' => $reactionable_id,
            'reactionable_type' =>  $this->contextService->getUserModel($user_id)
        ]);
    }
    public function unLike(){}
    public function comment(){}
    public function unComment(){}
}
