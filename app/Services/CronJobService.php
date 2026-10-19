<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sleep;
use App\Models\Visit;
use App\Models\Medicine;
use App\Models\MedicineDay;
use App\Models\Reservation;
use Faker\Provider\Medical;
use App\Models\MedicineTime;
use App\Enums\DaysToTakeEnum;
use App\Jobs\SendNotificationsJob;
use App\Traits\NotificationHelper;
use App\Enums\ReservationStatusEnum;
use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;
use App\Services\System\Notification\NotificationService;

/**
 * Class CronJobService.
 */
class CronJobService
{
    use NotificationHelper;

    public function __construct(
        protected NotificationService $notificationService,
    ) {}
    
}
