<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\Story\StoryService;
use App\Http\Requests\GetItemsRequest;
use App\Http\Requests\SubCategory\CreateSubCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Category\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function index(GetItemsRequest $request)
    {
        return success(
            $this->categoryService->getAll( $request->validated()),
            ApiMessages::MSG_SUCCESS,
            CategoryResource::class,
            $request->has('per_page')
        );
    }

    public function show($id)
    {
        return success(
            $this->categoryService->show($id),
            ApiMessages::MSG_SUCCESS,
            CategoryResource::class
        );
    }

    public function store(CreateCategoryRequest $request)
    {
        return createdSuccess(
            $this->categoryService->store($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function update(UpdateCategoryRequest $request , $id)
    {
        return success(
            $this->categoryService->update($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function destroy($id)
    {
        return success(
            $this->categoryService->destroy($id),
            ApiMessages::MSG_SUCCESS
        );
    }
}
