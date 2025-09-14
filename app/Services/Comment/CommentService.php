<?php

namespace App\Services\Comment;

use App\Models\Lesson;
use App\Models\Replay;
use App\Models\Comment;
use App\Enums\CommentStatusEnum;

/**
 * Class CommentService.
 */
class CommentService
{
    public function getForMobile($data , $lesson_id)
    {
        $comments =  Comment::exist()->where('lesson_id', $lesson_id)
                ->with('existReplays');

        return getOrPaginate($comments, $data);
    }

    public function store($data , $lesson_id)
    {
        if(! is_commented($lesson_id)) {
            Comment::create([
                'lesson_id' => $lesson_id,
                'user_id' => auth()->id(),
                'text' => $data['text'],
            ]);
        }
    }

    public function deleteComment($id)
    {
        Comment::findByIdOrFail($id)->update([
            'status' => CommentStatusEnum::DELETED->value,
        ]);
    }

    public function replay($data , $comment_id)
    {
        if(! is_replayed($comment_id)) {
            Replay::create([
                'comment_id' => $comment_id,
                'user_id' => auth()->id(),
                'text' => $data['text'],
            ]);
        }
    }

    public function deleteReplay($id)
    {
        Replay::findByIdOrFail($id)->update([
            'status' => CommentStatusEnum::DELETED->value,
        ]);
    }

    public function pinComment($comment_id)
    {
        $comment = Comment::findByIdOrFail($comment_id);
        $lesson = Lesson::findByIdOrFail($comment->lesson_id);

        $lesson->comments()->update([
            'is_pinned' => false,
        ]);
        $comment->update([
            'is_pinned' => true,
        ]);

        $lesson->save();
        $comment->save();
    }
}
