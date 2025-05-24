<?php

namespace App\Services\Category;

use App\Models\SubCategory;
use App\Constants\MediaCollection;
use App\Constants\ExceptionMessages;

/**
 * Class SubCategoryService.
 */
class SubCategoryService
{
    public function getAll($data)
    {
        return getOrPaginate(
            SubCategory::with('doctors'),
            $data
        );
    }

    public function show($id)
    {
        return SubCategory::findByIdOrFail($id , ['doctors']);
    }

    public function store($data)
    {
        $item = SubCategory::create($data);

        if(isset($data['image']))
            uploadFileOnMedia($data['image'] , $item , MediaCollection::SUB_CATEGORY_COLLECTION);

        $item->save();
    }

    public function update($data , $id)
    {
        $item =  SubCategory::findByIdOrFail($id);

        $item->update($data);

        $item->save();
    }

    public function destroy($id)
    {
        $item = SubCategory::findByIdOrFail($id);

        if($item->doctors()->count() > 0)
            return forbiddenFailure(null , ExceptionMessages::MSG_CAN_NOT_DELETE_CUZ_HAS_RELATED_ITEMS);

        $item->delete();
    }

    public function getByCategories($data)
    {
        return getOrPaginate(
            SubCategory::filter($data)->with('category'),
            $data
        );
    }




}
