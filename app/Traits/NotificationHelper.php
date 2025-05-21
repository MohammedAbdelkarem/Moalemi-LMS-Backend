<?php

namespace App\Traits;

use App\Models\System\Notification\Notification;
use App\Models\Users\Profile\UserDevice;
use Illuminate\Support\Facades\DB;

trait NotificationHelper
{
    /**
     * Sends a notification to a user directly, without queuing.
     * If $shouldCreate is true, creates a new notification and saves it to the database.
     * If $shouldCreate is false, does not create a new notification and only sends the notification to the user.
     *
     * @param int $targeted_user_id The id of the user to send the notification to
     * @param string $title The title of the notification
     * @param string $body The body of the notification
     * @param string $type The type of the notification
     * @param mixed $created_by The user who created the notification
     * @param string $local The notification local language
     * @param bool $public Whether the notification should be public
     * @param string $page The page the notification should redirect to
     * @param bool $clickable Whether the notification should be clickable
     * @param string $requestedID The requested id of the notification for clickable case
     * @param array $extraData Any extra data to be stored with the notification
     * @param bool $shouldCreate Whether to create a new notification in the database
     * @param array $additionalData Any additional data to be sent with the notification
     * @return Notification|bool The created notification if $shouldCreate is true, or true if $shouldCreate is false
     */
    protected function sendDirectNotification(
        int $targeted_user_id,
        string $title,
        string $body,
        string $type,
        $createdBy,
        string $local = null,
        bool $public = false,
        string $page = "/home",
        bool $clickable = false,
        string $requestedID = "",
        array $extraData = null,
        bool $shouldCreate = true,
        array $additionalData = [],
    ) {
        DB::beginTransaction();
        if (!$clickable) $requestedID = "";
        if ($shouldCreate) {
            $notification = $this->createNotification(
                $title,
                $body,
                $type,
                $createdBy,
                $public,
                $page,
                $clickable,
                $requestedID,
                $extraData
            );
            $notification->receivers()->attach($targeted_user_id);
        }

        $this->sendNotification(
            UserDevice::query()->where('user_id', $targeted_user_id)
                ->whereHas("user", function ($query) {
                    $query->where('active_notifications', true);
                })
                ->pluck('notification_token')
                ->toArray(),
            $title,
            $body,
            $page,
            $local ?? app()->getLocale(),
            $additionalData
        );
        DB::commit();

        if ($shouldCreate)
            return $notification;
        return true;
    }

    public function notificationMessage($message, $attributes = [])
    {
        return json_encode(["message" => $message, "attributes" => $attributes]);
    }

    /**
     * Create a new notification.
     *
     * @param string $title
     * @param string $body
     * @param string $type
     * @param mixed $createdBy
     * @param bool $public
     * @param string $page
     * @param bool $clickable
     * @param string $requestedID
     * @param array $extraData
     * @return Notification
     */
    protected function createNotification(string $title, string $body, string $type, $createdBy, bool $public = false, string $page = "/home", bool $clickable = false, string $requestedID = "", $extraData = null)
    {
        DB::beginTransaction();
        $notification = Notification::create([
            "title"         => $title,
            "body"          => $body,
            "type"          => $type,
            "created_by"    => $createdBy,
            "is_public"     => $public,
            "page"          => $page,
            "clickable"     => $clickable,
            "requested_id"  => $requestedID,
            "extra_data"    => $extraData
        ]);
        DB::commit();
        return $notification;
    }

    // protected function sendNotification2(array $tokens = [], string $title, string $body, $page = '/home', $additionalData = null)
    // {
    //     $div = 500; //between 1 -> 1000
    //     $start = 0;
    //     $size = sizeof($tokens);
    //     if ($size != 0)
    //         for ($i = 0; $i < ceil($size / $div); $i++) {
    //             $tokensSubArray = array_slice($tokens, $start, 200);
    //             // $SERVER_API_KEY = env('Server_Key');
    //             $credentialsFilePath = Storage::path('json/kafo-platform-firebase.json');
    //             $client = new GoogleClient();
    //             $client->setAuthConfig($credentialsFilePath);
    //             $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    //             $client->fetchAccessTokenWithAssertion();
    //             $token = $client->getAccessToken();
    //             $SERVER_API_KEY = $token['access_token'];
    //             // $data["registration_ids"] = $tokensSubArray;
    //             // $data["token"] = ["fTl5O-olJEBW3yI6geobdm:APA91bHn41uXRh8GROfOs41Ry9dem5XNfoj2LWV8KaGePugMc3yE93D3ko80rWAFvKHQ2KPhOJazdfHNRy_b3JbINKGnYXF-LXzEUgEcKz3amDgpk0OUUDqTPtHTzsODxMxZuQD626_Y"];
    //             $data["message"] = [
    //                 "token" => "dn5rDIYCfrl_bZs-ji_mrN:APA91bFqrXcjuZ8iIjOKGThDeEIwTLciKUramZm0Efb8HGdRtw-78QUJuSGGC8KzODKppEtBoU0jzikNwI8p5vLisJVsSkay3z__MZZZ5dtkX0-Qus4xddnQAYo6uusJzhazaYX6v1kz",
    //                 "notification" => [
    //                     "title" => $title,
    //                     "body" => $body,
    //                     // "sound" => "default",
    //                 ],
    //                 "data" => [
    //                     "click_action" => "FLUTTER_NOTIFICATION_CLICK",
    //                     "page" => $page,
    //                 ],
    //             ];
    //             // $data["data"] = [
    //             //     "click_action" => "FLUTTER_NOTIFICATION_CLICK",
    //             //     "page" => $page,
    //             // ];
    //             $dataString = json_encode($data);
    //             $headers = [
    //                 'X-CSRF-TOKEN' => csrf_token(),
    //                 'Authorization: Bearer ' . $SERVER_API_KEY,
    //                 'Content-Type: application/json',
    //             ];
    //             $ch = curl_init();
    //             curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/kafo-platform/messages:send');
    //             curl_setopt($ch, CURLOPT_POST, true);
    //             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //             $response = curl_exec($ch);
    //             //
    //             sleep(1);
    //             $start += $div;
    //         }
    //     return true;
    // }

    // protected function sendFireBaseNotification(array $tokens = [], string $title, string $body, $page = '/home', $additionalData = null)
    // {
    //     // $div = 500; //between 1 -> 1000
    //     // $start = 0;
    //     // $size = sizeof($tokens);
    //     // if ($size != 0)
    //     // for ($i = 0; $i < ceil($size / $div); $i++) {
    //     // $tokensSubArray = array_slice($tokens, $start, 200);
    //     // $SERVER_API_KEY = env('Server_Key');
    //     foreach ($tokens as $token) {
    //         $credentialsFilePath = Storage::path('json/kafo-platform-firebase.json');
    //         $client = new GoogleClient();
    //         $client->setAuthConfig($credentialsFilePath);
    //         $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    //         $client->fetchAccessTokenWithAssertion();
    //         $token = $client->getAccessToken();
    //         $SERVER_API_KEY = $token['access_token'];
    //         // $data["registration_ids"] = $tokensSubArray;
    //         // $data["token"] = ["fTl5O-olJEBW3yI6geobdm:APA91bHn41uXRh8GROfOs41Ry9dem5XNfoj2LWV8KaGePugMc3yE93D3ko80rWAFvKHQ2KPhOJazdfHNRy_b3JbINKGnYXF-LXzEUgEcKz3amDgpk0OUUDqTPtHTzsODxMxZuQD626_Y"];
    //         $data["message"] = [
    //             "token" => $token,
    //             "notification" => [
    //                 "title" => $title,
    //                 "body" => $body,
    //                 // "sound" => "default",
    //             ],
    //             "data" => [
    //                 "click_action" => "FLUTTER_NOTIFICATION_CLICK",
    //                 "page" => $page,
    //             ],
    //         ];
    //         // $data["data"] = [
    //         //     "click_action" => "FLUTTER_NOTIFICATION_CLICK",
    //         //     "page" => $page,
    //         // ];
    //         $dataString = json_encode($data);
    //         $headers = [
    //             'X-CSRF-TOKEN' => csrf_token(),
    //             'Authorization: Bearer ' . $SERVER_API_KEY,
    //             'Content-Type: application/json',
    //         ];
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/kafo-platform-801dc/messages:send');
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    //         $response = curl_exec($ch);
    //         //
    //         // sleep(1);
    //         // $start += $div;
    //         // }
    //         return true;
    //     }
    // }

    /**
     * Send a notification to given tokens.
     *
     * @param array $tokens Tokens to send the notification to.
     * @param string $title The title of the notification.
     * @param string $body The body of the notification.
     * @param string $page The page to open when the notification is clicked.
     * @param array $additionalData Additional data to send with the notification.
     *
     * @return void
     */
    public function sendNotification(array $tokens = [], string $title, string $body, $page = '/home', $local, $additionalData = null)
    {
        //TODO
    }
}