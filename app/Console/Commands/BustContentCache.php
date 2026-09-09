<?php

namespace App\Console\Commands;

use App\Support\ContentCache;
use Illuminate\Console\Command;

class BustContentCache extends Command
{
    protected $signature = 'cache:bust-content';
    protected $description = 'Bust all ContentService caches (shared hosting safe)';

    public function handle(): int
    {
        ContentCache::bust();
        $this->info('✅ Content cache buster updated.');
        return self::SUCCESS;
    }
}
