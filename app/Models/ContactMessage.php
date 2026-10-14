<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

#[Fillable(['name', 'email', 'phone', 'subject', 'message', 'is_read', 'ip_address', 'admin_notes'])]
class ContactMessage extends Model
{
    /**
     * Ensure table exists or attempt migration if missing.
     */
    public static function ensureTableExists(): void
    {
        try {
            if (! Schema::hasTable('contact_messages')) {
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Throwable) {
            // Silently continue if DB connection or permissions prevent runtime migration
        }
    }

    #[Scope]
    protected function unread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    #[Scope]
    protected function read(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }
}
