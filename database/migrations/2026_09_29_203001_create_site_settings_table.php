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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_url', 2048)->nullable();
            $table->string('instagram_url', 2048)->nullable();
            $table->string('whatsapp_url', 2048)->nullable();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image_url', 2048)->nullable();
            $table->string('about_eyebrow')->nullable();
            $table->string('about_title');
            $table->text('about_primary_text')->nullable();
            $table->text('about_secondary_text')->nullable();
            $table->string('about_image_url', 2048)->nullable();
            $table->unsignedSmallInteger('suite_count')->default(0);
            $table->string('newsletter_title')->nullable();
            $table->text('newsletter_text')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'id' => 1,
            'hotel_name' => 'EtnoCity Otel',
            'phone' => '+90 252 311 4000',
            'email' => 'concierge@etnocityotel.com',
            'address' => 'Gökçebel Koyu Mevkii, No. 44, 48400 Yalıkavak, Bodrum, Muğla',
            'hero_eyebrow' => 'Bodrum Yarımadası · Cape Artemis',
            'hero_title' => 'Zamansız Bir Kaçış, Sizin İçin Tasarlanmış',
            'hero_subtitle' => "Ege'nin huzuru, mimari zarafetle buluşuyor. Yirmi dört kaya süitinden oluşan bu sakin sığınak, yavaş yaşam ve duyusal iyileşme için tasarlandı.",
            'hero_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzFcOLacA9qwqiGrzAlNIQtAsnGKCAdoJmSWVBlURnlt5Rto7pwPy1bqfp7nxi4swrJxuvg9YNeskWfbh64H-uE3g9Akx1rLdNDLeHknsPdRzLwmjRFLN3BNlw28vlI_TkTsHRJy-cH7_lHf9kZ_Fx177GBVp6tU_9QTxwbr3SmJLIub_PTs1rQL3fU3UTOMFmYDNdGgI5vG3MdMnTaXkIg84rqfC7LOAeFT_w5MyjKyYUx7GtdyHNrg',
            'about_eyebrow' => "EtnoCity Otel'e Hoş Geldiniz",
            'about_title' => 'Deniz ve Yaşayan Taşla Uyum İçinde Yaratıldı',
            'about_primary_text' => "Bodrum'un kuzey burnundaki el değmemiş kayalıklara kurulu EtnoCity Otel, ham Akdeniz unsurları ile görkemli mimari arasındaki uzamsal bir diyalog olarak açılır. Yerel Muğla traverteni ve güneşte ağarmış sedir ağacından elle oyulmuş yirmi dört sığınağımız, korunaklı konfor ile deniz ufku arasındaki engeli ortadan kaldırıyor.",
            'about_secondary_text' => "Burada zaman yeniden ayarlanır. Sabahlar Türk Ege'sine bakan özel ses banyolarıyla başlar; öğleden sonralar tuzlu derin havuzlar ve antik zeytin bahçeleri arasında geçer; geceler yalnızca ay yansımaları ve mütevazı ateş çukurları ile aydınlanır.",
            'about_image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-8hMf1SQTks238yLzKOTSLidNO71s6HCPe6Ti_XiqSuZOKLCq20yYyDyUda6uLqDtwTBzJjG_0D0QxjPEOrx7gdVX_5aJHeUR3DVuF0DCQE8U6mxoGvLpJtjcdkfhOCoTWAfW5O4V_sKAZy2rJfjQoBGAPt4qaFu8fV4nOOAgJN1gGlA1bxidXKYrdIGTq_nNm06PGomfuyYgoNIyy0ewjogF7z61tXE0TZ89CXeW7-EXoBen7FlpJw',
            'suite_count' => 24,
            'newsletter_title' => 'Sessizliğe Davetler',
            'newsletter_text' => 'Mevsimsel hikâyeleri, mimari yenilikleri ve özel konaklama fırsatlarını herkesten önce keşfedin.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
