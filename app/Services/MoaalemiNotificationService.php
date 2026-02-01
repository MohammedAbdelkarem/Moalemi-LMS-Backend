<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Subject;
use App\Traits\NotificationHelper;
use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;
use Carbon\Carbon;

/**
 * Class MoaalemiNotificationService.
 */
class MoaalemiNotificationService
{
    use NotificationHelper;

    public function notifyForNewLesson($lesson)
    {
        $subject = $lesson->subject;
    
        // جلب المستخدمين
        $users_ids = User::whereNull('deactive_at')
            ->whereHas('unlockedContexts', function ($q) use ($subject) {
                $q->where('context_id', $subject->id)
                    ->where('context_type', Subject::class);
            })
            ->pluck('id');
    
        // تقسيم الإرسال إلى دفعات (كل دفعة 500 مستخدم)
        $users_ids->chunk(500)->each(function ($chunk) use ($subject , $lesson) {
    
            foreach ($chunk as $user_id) {
                try {
                    $this->sendDirectNotification(
                        $user_id,
                        $this->notificationMessage(NotificationMessages::NEW_LESSON_TITLE , [
                            "subject_name" => $subject->name
                        ]),
                        $this->notificationMessage(NotificationMessages::NEW_LESSON_BODY, [
                            "lesson_title" => $lesson->title,
                            "subject_name" => $subject->name
                        ]),
                        NotificationTypes::MOAALEMI->value,
                        'ar',
                        false,
                        "",
                        [],
                        true,
                        [],
                        true
                    );
                } catch (\Throwable $e) {
                    // سجل الخطأ بدون إيقاف الإرسال
                    Log::error("Failed to send new lesson notification to user {$user_id}: " . $e->getMessage());
                }
            }
    
            // راحة بسيطة بين كل دفعة لتخفيف الضغط على السيرفر
            sleep(1); // 1 second
        });
    }

    public function notifyForCompleteLesson($lesson)
    {
        $user = auth()->user();

        $lesson_name = $lesson->name;
        $next_lesson_name = next_lesson_name_for_notifications($lesson);

        $this->sendDirectNotification(
            $user->id,
            $this->notificationMessage(NotificationMessages::COMPLETE_LESSON_TITLE, [
                "lesson_title" => $lesson_name,
                "student_name" => $user->name
            ]),
            $this->notificationMessage(NotificationMessages::COMPLETE_LESSON_BODY, [
                "lesson_title" => $lesson_name,
                "next_lesson" => $next_lesson_name
            ]),
            NotificationTypes::MOAALEMI->value,
            'ar',
            false,
            "",
            [],
            true,
            [],
            true
        );
    }

    public function notifyForNewQuiz($quiz , $subject)
    {
        $users_ids = User::whereNull('deactive_at')
            ->whereHas('unlockedContexts', function ($q) use ($quiz) {
                $q->where('context_id', $quiz->context_id)
                    ->where('context_type', $quiz->context_type);
            })
            ->pluck('id');

        // تقسيم الإرسال إلى دفعات (كل دفعة 500 مستخدم)
        $users_ids->chunk(500)->each(function ($chunk) use ($subject , $quiz) {
    
            foreach ($chunk as $user_id) {
                try {
                    $this->sendDirectNotification(
                        $user_id,
                        $this->notificationMessage(NotificationMessages::NEW_QUIZ_TITLE , [
                            "subject_name" => $subject->name
                        ]),
                        $this->notificationMessage(NotificationMessages::NEW_QUIZ_BODY, [
                            "quiz_title" => $quiz->title,
                            "subject_name" => $subject->name,
                            "deadline" => Carbon::now()->addDay()->format('Y-m-d')
                        ]),
                        NotificationTypes::MOAALEMI->value,
                        'ar',
                        false,
                        "",
                        [],
                        true,
                        [],
                        true
                    );
                } catch (\Throwable $e) {
                    // سجل الخطأ بدون إيقاف الإرسال
                    Log::error("Failed to send new quiz notification to user {$user_id}: " . $e->getMessage());
                }
            }
    
            // راحة بسيطة بين كل دفعة لتخفيف الضغط على السيرفر
            sleep(1); // 1 second
        });
    }

    public function notifyForLowActivity()
    {
        $users = User::whereNull('deactive_at')
            ->where('updated_at', '<', Carbon::now()->subDays(3))
            ->get();

        $users->chunk(500)->each(function ($chunk) {
            foreach ($chunk as $user) {
                try {
                    $this->sendDirectNotification(
                        $user->id,
                        $this->notificationMessage(NotificationMessages::LOW_ACTIVITY_TITLE),
                        $this->notificationMessage(NotificationMessages::LOW_ACTIVITY_BODY, [
                            "student_name" => $user->name
                        ]),
                        NotificationTypes::MOAALEMI->value,
                        'ar',
                        false,
                        "",
                        [],
                        true,
                        [],
                        true
                    );
                } catch (\Throwable $e) {
                    Log::error("Failed to send low activity notification to user {$user_id}: " . $e->getMessage());
                }
            }

            sleep(1);
        });
    }

    public function notifyForCommentReply($lesson , $comment)
    {
        $user_id = $comment->user_id;

        $this->sendDirectNotification(
            $user_id,
            $this->notificationMessage(NotificationMessages::COMMENT_REPLY_TITLE),
            $this->notificationMessage(NotificationMessages::COMMENT_REPLY_BODY, [
                "lesson_title" => $lesson->name
            ]),
            NotificationTypes::MOAALEMI->value,
            'ar',
            false,
            "",
            [],
            true,
            [],
            true
        );
    }

    public function notifyForLessonQuestionAnswer($lesson_question)
    {
        $user_id = $lesson_question->student_id;

        $teacher_name = $lesson_question->teacher->name;
        $lesson_name = $lesson_question->lesson->name;

        $this->sendDirectNotification(
            $user_id,
            $this->notificationMessage(NotificationMessages::QUESTION_ANSWER_TITLE),
            $this->notificationMessage(NotificationMessages::QUESTION_ANSWER_BODY, [
                "teacher_name" => $teacher_name,
                "lesson_title" => $lesson_name
            ]),
            NotificationTypes::MOAALEMI->value,
            'ar',
            false,
            "",
            [],
            true,
            [],
            true
        );
    }
    
}
