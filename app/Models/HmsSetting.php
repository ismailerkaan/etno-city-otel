<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * HMS Online Booking credentials managed from the admin panel. When no row exists the env config is used.
 */
#[Fillable([
    'enabled',
    'base_url',
    'hotel_code',
    'api_key',
    'api_secret',
    'last_tested_at',
    'last_test_result',
])]
class HmsSetting extends Model
{
    public static function current(): ?self
    {
        return static::query()->first();
    }

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'api_key' => 'encrypted',
            'api_secret' => 'encrypted',
            'last_tested_at' => 'datetime',
        ];
    }
}
