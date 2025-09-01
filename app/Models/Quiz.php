<?php

namespace App\Models;

use App\Constants\Resources;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Quiz
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            null,
            Resources::QUIZ,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    // Relationships
    public function context()
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_question', 'quiz_id', 'question_id')
                    ->withPivot('priority')
                    ->withTimestamps();
    }

    public function quizResults(): HasMany
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('publish_status', PublishStatusEnum::PUBLISHED->value);
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }
}
