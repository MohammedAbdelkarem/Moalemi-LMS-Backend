<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CronJobService;

class ReservationDailyReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected CronJobService $cronJobService;

    public function __construct(CronJobService $cronJobService)
    {
        parent::__construct();
        $this->cronJobService = $cronJobService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->cronJobService->remindForReservationsDaily();
    }
}
