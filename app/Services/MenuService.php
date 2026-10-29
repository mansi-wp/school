<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class MenuService
{
    /**
     * Invalidate all sidebar cache keys across users
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
}
