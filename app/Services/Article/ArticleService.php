<?php

namespace App\Services\Article;

use App\Models\Article;
use App\Constants\MediaCollection;
use App\Services\Base\ContextService;
use App\Services\PatientNotificationService;

/**
 * Class ArticleService.
 */
class ArticleService
{
    public function __construct(
        protected ContextService $contextService,
        protected PatientNotificationService $patientNotificationService,
    ) {}
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
        
        return Article::findByIdOrFail($id , [
            'existsReactions.user' ,
             'existsReactions.existReplays' ,
              'existsComments.user',
              'existsComments.existReplays',
              'doctor.subCategories.category'
            ]);
    }

    public function showForAdmin($id)
    {
        // $this->increaseArticleView($id);
        
        return Article::findByIdOrFail($id , [
            'reactions.user' ,
             'reactions.replaies' ,
              'comments.user',
              'comments.replaies',
              'doctor.subCategories.category'
            ]);
    }

    public function store($data)
    {
        $data['doctor_id'] = doctor_id();
        
        $item = Article::create($data);

        if(isset($data['images']))
            uploadFilesOnMedia($data['images'] , $item , MediaCollection::ARTICLE_COLLECTION);

        $item->save();

        $this->patientNotificationService->notifyForArticles($item);
    }

    public function update($data , $id)
    {
        $item =  Article::findByIdOrFail($id);

        $item->update($data);
        
        if(isset($data['images']))
            updateFilesOnMedia($data['images'] , $item , MediaCollection::ARTICLE_COLLECTION);

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

    public function filterArticles($data)
    {
        return getOrPaginate(
            Article::filter($data),
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
