<?php

namespace App\Services\Article;

use App\Models\Article;
use App\Constants\MediaCollection;
use App\Services\Base\ContextService;

/**
 * Class ArticleService.
 */
class ArticleService
{
    protected $contextService;
    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }
    public function getAll($data)
    {
        return getOrPaginate(
            Article::orderByDesc('created_at'),
            $data
        );
    }

    public function search($data)
    {
        return getOrPaginate(
            Article::filter($data),
            $data
        );
    }

    public function show($id)
    {
        $this->increaseArticleView($id);
        
        return Article::findByIdOrFail($id , ['reactions' , 'reactions.user']);
    }

    public function store($data)
    {
        $data['doctor_id'] = doctor_id();
        
        $item = Article::create($data);

        if(isset($data['image']))
            uploadFileOnMedia($data['image'] , $item , MediaCollection::ARTICLE_COLLECTION);


        $item->save();
    }

    public function update($data , $id)
    {
        $item =  Article::findByIdOrFail($id);

        $item->update($data);

        $item->save();
    }

    public function destroy($id)
    {
        $item = Article::findByIdOrFail($id);

        $item->delete();
    }

    public function getMyArticles($data)
    {
        return getOrPaginate(
            Article::where('doctor_id' , doctor_id()),
            $data
        );
    }

    private function increaseArticleView($id)
    {
        $article = Article::findByIdOrFail($id);

        if(! $article->ArticleViews()->where('user_id' , auth()->id())->exists())
        {
            $article->ArticleViews()->create([
                'user_id' => auth()->id(),
            ]);

            $article->views++;

            $article->save();
        }
    }
}
