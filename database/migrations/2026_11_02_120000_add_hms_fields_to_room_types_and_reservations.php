<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->unsignedInteger('hms_room_type_id')->nullable()->after('slug');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->json('child_ages')->nullable()->after('children');
            $table->unsignedInteger('hms_room_type_id')->nullable()->after('room_name');
            $table->unsignedInteger('hms_rate_plan_id')->nullable()->after('hms_room_type_id');
            $table->string('board_name')->nullable()->after('hms_rate_plan_id');
            $table->string('hms_price_key', 20)->nullable()->after('board_name');
            $table->boolean('non_refundable')->default(false)->after('hms_price_key');
            $table->json('daily_rates')->nullable()->after('non_refundable');
            $table->unsignedBigInteger('hms_booking_id')->nullable()->after('daily_rates');
            $table->timestamp('hms_synced_at')->nullable()->after('hms_booking_id');
            $table->text('hms_sync_error')->nullable()->after('hms_synced_at');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'child_ages', 'hms_room_type_id', 'hms_rate_plan_id', 'board_name', 'hms_price_key',
                'non_refundable', 'daily_rates', 'hms_booking_id', 'hms_synced_at', 'hms_sync_error',
            ]);
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('hms_room_type_id');
        });
    }
};
