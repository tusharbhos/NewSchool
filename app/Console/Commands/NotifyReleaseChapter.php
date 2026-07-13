<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\HomeController;
use App\Jobs\NotifyReleaseChapterJob;
use Log;

class NotifyReleaseChapter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-release-chapter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::debug("Command Call NotifyReleaseChapterJob");
        dispatch(new NotifyReleaseChapterJob());
    }
}
