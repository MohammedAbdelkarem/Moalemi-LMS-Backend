<?php

namespace App\Http\Controllers\Mobile\File;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Services\File\FileService;
use App\Http\Controllers\Controller;
use App\Http\Resources\File\FileResource;

class FileController extends Controller
{
    public function __construct(
        protected FileService $fileService,
    ) {}

    public function search(Request $request)
    {
        return success(
            $this->fileService->search($request->all(), $request->student_id ?? auth()->id()),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
        );
    }

    public function filter(Request $request)
    {
        return success(
            $this->fileService->filter($request->all(), $request->student_id ?? auth()->id()),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
        );
    }
}
