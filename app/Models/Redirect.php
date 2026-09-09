<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'old_path',
        'new_path',
        'status_code',
        'is_active',
        'hits',
        'last_hit_at',
    ];

    protected $attributes = [
        'status_code' => 301,
        'is_active' => true,
        'hits' => 0,
    ];

    protected $casts = [
        'status_code' => 'integer',
        'is_active'   => 'boolean',
        'hits'        => 'integer',
        'last_hit_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $redirect) {
            $redirect->old_path = self::normalizePath($redirect->old_path, allowAbsolute: false);

            // new_path may be absolute URL OR internal path
            $redirect->new_path = self::normalizePath($redirect->new_path, allowAbsolute: true);

            // Ensure status code is a safe redirect code
            $status = (int) ($redirect->status_code ?? 301);
            if (!in_array($status, [301, 302, 307, 308], true)) {
                $status = 301;
            }
            $redirect->status_code = $status;

            // Never allow negative hits
            $redirect->hits = max(0, (int) ($redirect->hits ?? 0));
        });
    }

    private static function normalizePath(?string $value, bool $allowAbsolute): string
    {
        $value = trim((string) $value);

        if ($allowAbsolute && preg_match('#^https?://#i', $value)) {
            // Keep absolute URLs as-is (just trim)
            return $value;
        }

        // Normalize internal paths
        if ($value === '' || $value === '/') {
            return '/';
        }

        $value = '/' . ltrim($value, '/');
        $value = rtrim($value, '/');

        return $value === '' ? '/' : $value;
    }
}
