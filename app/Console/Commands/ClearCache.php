<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ClearCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing app cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('cache:clear');
        Log::info('App Cache Cleared at Midnight');
    }
}
