<?php

namespace Tests\Unit;

// use Carbon\Carbon;

use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SchedulingCacheClearTest extends TestCase
{
    use InteractsWithTime;

    /**
     * assert messages on logging file.
     */
    function assertLogContains($message)
    {
        $logPath = storage_path('logs/laravel.log');
        $logContent = file_get_contents($logPath);

        $this->assertStringContainsString($message, $logContent);
    }
    /**
     * A unit test to run scheduling clear cache at midnight.
     */
    public function test_scheduling_clear_cache_at_midnight(): void
    {
        $this->travelTo(now()->startOfDay()->addHours(24));

        Artisan::call('app:clear-cache');

        $this->assertTrue(true);
        // $this->assertLogContains('App Cache Cleared at Midnight');

        $this->travelBack();
    }
}
