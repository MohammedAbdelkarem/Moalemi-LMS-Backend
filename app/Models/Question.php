<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];



    // Relationships
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function subUnit(): BelongsTo
    {
        return $this->belongsTo(SubUnit::class, 'sub_unit_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function quizzes(): BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_question', 'question_id', 'quiz_id')
                    ->withPivot('priority')
                    ->withTimestamps();
    }

    public function studentAnswers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOneSelect($query)
    {
        return $query->where('type', 'one_select');
    }

    public function scopeMultipleSelect($query)
    {
        return $query->where('type', 'multiple_select');
    }

    public function scopeTrueFalse($query)
    {
        return $query->where('type', 'true_false');
    }

    public function scopeText($query)
    {
        return $query->where('type', 'text');
    }
}
