<?php

namespace App\Services\System\Info;

use App\Constants\Resources;
use App\Models\System\Info\FAQ;
use App\Models\System\Info\FaqCategory;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;

class FAQService extends MainService
{
    public function index($per_page, $search = null)
    {
        return FaqCategory::query()
            ->withWhereHas('faqs', function ($query) use ($search) {
                $query->when($search, function ($query) use ($search) {
                    $query->whereAny(['question->ar', 'question->en', 'answer->ar', 'answer->en'], 'like', "%" . $search . "%");
                });
                if (auth()->user()?->role_id != 3) {
                    $query->with([
                        "category"  => fn($q) => $q->select('id', 'name'),
                        "updater"   => fn($q) => $q->select('id', 'name')
                    ]);
                }
                if (auth()->user()?->role_id == 3) {
                    $query->where('is_draft', 0);
                }
            })
            ->with('updater')
            ->paginate($per_page);
    }

    public function store($validatedData)
    {
        FAQ::create(
            [
                'question' => [
                    'en' => $validatedData["question"]["en"],
                    'ar' => $validatedData["question"]["ar"],
                ],
                'answer' => [
                    'en' => $validatedData["answer"]["en"],
                    'ar' => $validatedData["answer"]["ar"],
                ],
                "faq_category_id" => $validatedData["category_id"],
                "is_draft"  => $validatedData["is_draft"],
                "update_by" => auth()->id(),
            ]
        );
    }

    public function show($id)
    {
        return findByIdOrFail(FAQ::class, $id, Resources::ITEM);
    }

    public function update($validatedData, $id)
    {
        $faq = findByIdOrFail(FAQ::class, $id);

        $faq->update([
            'question' => [
                'en' => $validatedData["question"]["en"],
                'ar' => $validatedData["question"]["ar"],
            ],
            'answer' => [
                'en' => $validatedData["answer"]["en"],
                'ar' => $validatedData["answer"]["ar"],
            ],
            "faq_category_id" => $validatedData["category_id"],
            "is_draft"  => $validatedData["is_draft"],
            "update_by" => auth()->id(),
        ]);
    }

    public function destroy($id)
    {
        findByIdOrFail(FAQ::class, $id)->delete();
    }
}