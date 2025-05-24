<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetItemsRequest;
use App\Http\Requests\GetSubCategoriesRequest;
use App\Services\Category\SubCategoryService;
use App\Http\Resources\SubCategory\SubCategoryResource;
use App\Http\Requests\SubCategory\CreateSubCategoryRequest;
use App\Http\Requests\SubCategory\UpdateSubCategoryRequest;

class SubCategoryController extends Controller
{
    public function __construct(
        protected SubCategoryService $subCategoryService,
    ) {}

    public function index(GetItemsRequest $request)
    {
        return success(
            $this->subCategoryService->getAll( $request->validated()),
            ApiMessages::MSG_SUCCESS,
            SubCategoryResource::class,
            $request->has('per_page')
        );
    }

    public function show($id)
    {
        return success(
            $this->subCategoryService->show($id),
            ApiMessages::MSG_SUCCESS,
            SubCategoryResource::class
        );
    }

    public function store(CreateSubCategoryRequest $request)
    {
        return createdSuccess(
            $this->subCategoryService->store($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function update(UpdateSubCategoryRequest $request , $id)
    {
        return success(
            $this->subCategoryService->update($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function destroy($id)
    {
        return success(
            $this->subCategoryService->destroy($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getBycategories(GetSubCategoriesRequest $request)
    {
        return success(
            $this->subCategoryService->getBycategories($request->validated()),
            ApiMessages::MSG_SUCCESS,
            SubCategoryResource::class,
            $request->has('per_page')
        );
    }
}
