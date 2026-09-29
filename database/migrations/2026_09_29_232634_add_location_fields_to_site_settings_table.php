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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('location_eyebrow')->nullable()->after('testimonials_description');
            $table->text('location_eyebrow_en')->nullable()->after('testimonials_description_en');
            $table->text('location_title')->nullable()->after('location_eyebrow');
            $table->text('location_title_en')->nullable()->after('location_eyebrow_en');
            $table->text('location_description')->nullable()->after('location_title');
            $table->text('location_description_en')->nullable()->after('location_title_en');
            $table->text('location_map_embed_url')->nullable()->after('location_description');
            $table->text('location_coordinates')->nullable()->after('location_map_embed_url');
            $table->text('location_address')->nullable()->after('location_coordinates');
            $table->text('location_pin_label')->nullable()->after('location_address');
        });

        DB::table('site_settings')->where('id', 1)->update([
            'location_eyebrow' => 'Yarımadaya Varış',
            'location_eyebrow_en' => 'Peninsula Arrival',
            'location_title' => 'Sakin Ama Kolayca Ulaşılabilir',
            'location_title_en' => 'Secluded Yet Effortlessly Accessible',
            'location_description' => "Cape Artemis'in el değmemiş batı burnunda yer alan AURA, antik kaya sırtlarıyla korunan mutlak bir sükûnet sunarken, canlı Bodrum Rivierası'na dakikalar mesafededir.",
            'location_description_en' => 'Situated on the pristine western bluff of Cape Artemis, AURA offers absolute tranquility shielded by ancient rock ridges, while remaining minutes from the vibrant Bodrum Riviera.',
            'location_map_embed_url' => 'https://maps.google.com/maps?q=37.122889,27.278917&hl=tr&z=14&output=embed',
            'location_coordinates' => '37°07\'22.4"N 27°16\'44.1"E',
            'location_address' => 'Cape Artemis, Gökçebel Koyu, Bodrum',
            'location_pin_label' => 'EtnoCity Otel',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'location_eyebrow',
                'location_eyebrow_en',
                'location_title',
                'location_title_en',
                'location_description',
                'location_description_en',
                'location_map_embed_url',
                'location_coordinates',
                'location_address',
                'location_pin_label',
            ]);
        });
    }
};
