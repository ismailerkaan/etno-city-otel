<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
            if (Schema::hasColumn('hero_slides', 'alt_tr')) {
                $table->dropColumn(['alt_tr', 'alt_en']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
            $table->string('alt_tr')->nullable();
            $table->string('alt_en')->nullable();
        });
    }
};
