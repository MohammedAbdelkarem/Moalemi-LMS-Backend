<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Banner;
use App\Traits\StorageHelper;
use App\Models\Users\Reel\Reel;
use Illuminate\Console\Command;

class BannerRemoverCommand extends Command
{
    use StorageHelper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banner:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to delete expired banners with there media';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $banners = Banner::where('expire_at', '<', Carbon::now()->format('Y-m-d H:i:s'))->get();
        foreach ($banners as $banner) {
            $this->storageDelete($banner->img_url);
        }
        $banners->delete();
    }
}
