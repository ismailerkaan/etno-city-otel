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
        Schema::create('nearby_places', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 50)->default('location_on');
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('description')->nullable();
            $table->string('description_en')->nullable();
            $table->string('distance')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('nearby_places')->insert([
            [
                'icon' => 'flight',
                'title' => 'Bodrum-Milas Airport (BJV)',
                'title_en' => 'Bodrum-Milas Airport (BJV)',
                'description' => 'Özel Şoför: 35 dk · Doğrudan Helikopter: 10 dk',
                'description_en' => 'Private Chauffeur: 35 min · Direct Helicopter: 10 min',
                'distance' => '38 km',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'sailing',
                'title' => 'Yalıkavak Mega-Yat Marinası',
                'title_en' => 'Yalikavak Mega-Yacht Marina',
                'description' => 'Özel Tekne: 12 dk · Şoför: 15 dk',
                'description_en' => 'Private Tender Boat: 12 min · Chauffeur: 15 min',
                'distance' => '9 km',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'icon' => 'anchor',
                'title' => 'Bodrum Kalesi & Tarihi Liman',
                'title_en' => 'Bodrum Castle & Old Town Harbor',
                'description' => 'Özel Şoför: 25 dk',
                'description_en' => 'Private Chauffeur: 25 min',
                'distance' => '22 km',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nearby_places');
    }
};
