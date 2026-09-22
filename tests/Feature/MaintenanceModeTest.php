<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    public function test_artisan_down_renders_the_503_view_without_error(): void
    {
        // The maintenance view once referenced an undefined $exception variable
        // (only normal exception-handler views receive that), which made
        // `php artisan down` itself fail during deploys.
        try {
            $exitCode = Artisan::call('down');

            $this->assertSame(0, $exitCode, Artisan::output());
        } finally {
            Artisan::call('up');
        }
    }
}
