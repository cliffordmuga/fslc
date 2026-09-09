<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneStaleCache extends Command
{
    protected $signature = 'cache:prune-stale {--store= : Cache store to prune (default: config cache.default)}';

    protected $description = 'Delete expired rows from a database-backed cache store';

    public function handle(): int
    {
        $store = $this->option('store') ?: config('cache.default');
        $config = config("cache.stores.{$store}");

        if (($config['driver'] ?? null) !== 'database') {
            $this->info("Cache store [{$store}] is not database-backed — nothing to prune.");

            return self::SUCCESS;
        }

        // Expired page-cache keys (page:v1:*) are never read again after a cache-buster
        // bump, so the database store never evicts them on its own — this reclaims them.
        $table = $config['table'] ?? 'cache';
        $connection = $config['connection'] ?? config('database.default');

        $deleted = DB::connection($connection)
            ->table($table)
            ->where('expiration', '<=', now()->getTimestamp())
            ->delete();

        $this->info("Pruned {$deleted} expired row(s) from [{$table}].");

        return self::SUCCESS;
    }
}
