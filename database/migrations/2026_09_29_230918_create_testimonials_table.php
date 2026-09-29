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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_title')->nullable();
            $table->string('author_title_en')->nullable();
            $table->text('comment');
            $table->text('comment_en')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('avatar_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('testimonials')->insert([
            [
                'author_name' => 'Lady Helena Vance-Cross',
                'author_title' => 'Londra, Birleşik Krallık · Uçurum Süiti 12',
                'author_title_en' => 'London, United Kingdom · Cliffside Suite 12',
                'comment' => 'Her detayın fısıldadığı, asla bağırmadığı, ruhani bir cennet. Karşılaştığımız en sessiz, en zarif lüks.',
                'comment_en' => 'An ethereal sanctuary where every detail whispers rather than shouts. The quietest, most understated luxury we have ever encountered.',
                'rating' => 5,
                'avatar_url' => null,
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'author_name' => 'Alexander & Sophie Laurent',
                'author_title' => 'Paris, Fransa · Zeytin Bahçesi Villası',
                'author_title_en' => 'Paris, France · The Olive Grove Villa',
                'comment' => 'Ege’nin sonsuz maviliğine uyanmak ve kişiye özel şefimizin hazırladığı lezzetleri tatmak büyüleyiciydi. Tamamen yenilenmiş hissediyoruz.',
                'comment_en' => 'Waking up to the endless Aegean blue and savoring bespoke culinary journeys was unforgettable. We leave completely rejuvenated and inspired.',
                'rating' => 5,
                'avatar_url' => null,
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'author_name' => 'Marcus Von Berg',
                'author_title' => 'Zürih, İsviçre · Cape Ufuk Süiti',
                'author_title_en' => 'Zurich, Switzerland · Cape Horizon Suite',
                'comment' => 'Akustik huzur ve doğal kaya mimarisinin dinginliği benzersiz. Özel ses banyoları ve gün batımı terası hayatımın en iyi dinlenme deneyimiydi.',
                'comment_en' => 'The acoustic serenity and cliffside architecture are truly peerless. The private sound baths and sunset terrace were the highlight of our stay.',
                'rating' => 5,
                'avatar_url' => null,
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
        Schema::dropIfExists('testimonials');
    }
};
