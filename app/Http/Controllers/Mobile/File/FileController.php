<?php

namespace App\Http\Controllers\Mobile\File;

use App\Models\Subject;
use App\Models\Unit;
use App\Models\SubUnit;
use App\Models\Lesson;
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

    public function subjectFiles(Request $request)
    {
        return success(
            $this->fileService->getPurchasedFiles($request->student_id ??auth()->id(), $request->all(), Subject::class),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
            $request->has('per_page')
        );
    }

    public function unitFiles(Request $request)
    {
        return success(
            $this->fileService->getPurchasedFiles($request->student_id ??auth()->id(), $request->all(), Unit::class),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
            $request->has('per_page')
        );
    }

    public function subUnitFiles(Request $request)
    {
        return success(
            $this->fileService->getPurchasedFiles($request->student_id ??auth()->id(), $request->all(), SubUnit::class),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
            $request->has('per_page')
        );
    }

    public function lessonFiles(Request $request)
    {
        return success(
            $this->fileService->getPurchasedFiles($request->student_id ??auth()->id(), $request->all(), Lesson::class),
            ApiMessages::MSG_SUCCESS,
            FileResource::class,
            $request->has('per_page')
        );
    }
}
