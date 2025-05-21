<?php

namespace App\Console\Commands;

use App\Models\Administration\Reel\Reel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReelRemoverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reel:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to delete expired reels with there media';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reels = Reel::where('expire_at', '<', Carbon::now()->format('Y-m-d H:i:s'))->get();
        foreach ($reels as $reel) {
            $this->storageDelete($reel->media_url);
        }
        $reels->delete();
    }
}
