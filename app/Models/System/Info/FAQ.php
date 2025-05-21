<?php

namespace App\Models\System\Info;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    use HasFactory, HasTranslations;
    protected $table = "faq";
    protected $fillable = [
        "faq_category_id",
        "question",
        "answer",
        "is_draft",
        "update_by"
    ];
    public $translatable = ['question', 'answer'];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
