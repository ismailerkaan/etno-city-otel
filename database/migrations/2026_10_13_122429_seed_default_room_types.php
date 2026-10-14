<?php

use Database\Seeders\RoomTypeSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new RoomTypeSeeder)->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Room types may already have reservations; preserve their business data on rollback.
    }
};
