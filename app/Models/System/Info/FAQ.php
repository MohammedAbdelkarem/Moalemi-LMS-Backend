<?php

namespace App\Models\System\Info;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FAQ extends Model
{
    use HasFactory;
    protected $table = "faqs";
    protected $fillable = [
        "faq_category_id",
        "question",
        "answer",
        "is_draft",
        "update_by"
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}