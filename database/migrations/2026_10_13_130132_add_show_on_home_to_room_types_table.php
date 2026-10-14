<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->boolean('show_on_home')->default(false)->after('is_active');
            $table->index(['show_on_home', 'is_active', 'sort_order']);
        });

        DB::table('room_types')->where('sort_order', '<=', 3)->update(['show_on_home' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropIndex(['show_on_home', 'is_active', 'sort_order']);
            $table->dropColumn('show_on_home');
        });
    }
};
