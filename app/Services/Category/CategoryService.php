<?php

namespace App\Services\Category;

use App\Constants\ExceptionMessages;
use App\Models\Category;
use App\Constants\MediaCollection;

/**
 * Class CategoryService.
 */
class CategoryService
{
    public function getAll($data)
    {
        return getOrPaginate(
            Category::with('subCategories'),
            $data
        );
    }

    public function show($id)
    {
        return Category::findByIdOrFail($id  , ['subCategories']);
    }

    public function store($data)
    {
        $category = Category::create($data);

        if(isset($data['image']))
            uploadFileOnMedia($data['image'] , $category , MediaCollection::CATEGORY_COLLECTION);

        $category->save();
    }

    public function update($data , $id)
    {
        $category =  Category::findByIdOrFail($id);

        $category->update($data);

        $category->save();
    }

    public function destroy($id)
    {
        $category = Category::findByIdOrFail($id);

        if($category->subCategories()->count() > 0)
            return forbiddenFailure(null , ExceptionMessages::MSG_CAN_NOT_DELETE_CUZ_HAS_RELATED_ITEMS);

        $category->delete();
    }
}
