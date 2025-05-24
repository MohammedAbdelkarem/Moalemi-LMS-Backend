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
            Article::query(),
            $data
        );
    }

    public function show($id)
    {
        return Article::findByIdOrFail($id);
    }

    public function store($data)
    {
        $data['doctor_id'] = 1;
        
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
            Article::where('doctor_id' , 1),
            $data
        );
    }
}
