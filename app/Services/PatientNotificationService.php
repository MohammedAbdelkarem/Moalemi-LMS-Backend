<?php

namespace App\Services;

use App\Models\User;
use App\Models\Article;
use App\Models\Favorite;
use App\Jobs\SendNotificationsJob;
use App\Traits\NotificationHelper;
use App\Models\NotificationManagement;
use App\Constants\NotificationMessages;
use App\Models\Users\Profile\UserDevice;
use App\Enums\Notifications\NotificationTypes;

/**
 * Class PatientNotificationService.
 */
class PatientNotificationService
{
    use NotificationHelper;
    public function getSettings()
    {
        return auth()->user()->notification_management;
    }

    public function update($id , $type)
    {
        $setting = NotificationManagement::find($id);

        $column = $type . '_notification';

        $setting->$column = ($setting->$column == 1) ? 0 : 1;

        $setting->save();
    }

    public function notifyForArticles($article)
    {
        
        $users_tokens_list = $this->getUsersTokensList(true , 'articles');

        $favorite_users_tokens_list = $this->getUsersTokensListForFavoriteArticles($article);
        //Create Notification
        $userNotification = $this->createNotification(
            title: $this->notificationMessage(NotificationMessages::NEW_ARTICLE_TITLE) ,
            body: $this->notificationMessage(NotificationMessages::NEW_ARTICLE_BODY , ['title' => $article->title]),
            type: NotificationTypes::ARTICLES->value,
            is_public: false
        );

        $favoriteUserNotification = $this->createNotification(
            title: $this->notificationMessage(NotificationMessages::DOCTOR_ARTICLE_TITLE) ,
            body: $this->notificationMessage(NotificationMessages::DOCTOR_ARTICLE_BODY , ['title' => $article->title , 'doctor' => $article->doctor->clinic_name]),
            type: NotificationTypes::ARTICLES->value,
            is_public: false
        );

        //Dispatch Job To Send Notification
        dispatch(new SendNotificationsJob(
            tokens: $users_tokens_list,
            notification: $userNotification,
            shouldTranslate: true,
        ));

        dispatch(new SendNotificationsJob(
            tokens: $favorite_users_tokens_list,
            notification: $favoriteUserNotification,
            shouldTranslate: true,
        ));
    }

    private function getUsersTokensList($notification_management = false , $string = "")
    {
        $usersIdsList = User::where('role_id' , 4)
            ->pluck('id')
            ->toArray();

        $usersTokensList = UserDevice::query()
            ->whereIn('user_id', $usersIdsList)
            ->whereHas('user', function ($query) use ($notification_management, $string) {
                $query->whereNull('deactive_at')
                    ->where('active_notifications', true)
                    ->when($notification_management, function ($q) use ($string) {
                        $q->whereHas('notification_management', function ($q2) use ($string) {
                            $q2->where($string . '_notification', 1);
                        });
                    });
            })
            ->pluck('notification_token')
            ->toArray();

        return $usersTokensList;
    }

    private function getUsersTokensListForFavoriteArticles($article)
    {
        $favoriteUsersIds = Favorite::where('favoritable_type' , Article::class)
            ->where('favoritable_id' , $article->id)
            ->pluck('user_id')
            ->toArray();
            // dd($favoriteUsersIds);

        $favorite_users_tokens_list = UserDevice::query()
            ->whereIn('user_id', $favoriteUsersIds)
            ->whereHas("user", function ($query) {
                $query->whereNull("deactive_at")->where('active_notifications', true)
                    ->whereHas('notification_management' , function ($q) {
                        $q->where('articles_notification' , 1);
                    });
            })
            ->pluck('notification_token')
            ->toArray();

            // dd($favorite_users_tokens_list);
        return $favorite_users_tokens_list;
    }
}
