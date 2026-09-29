<x-layouts.reservation>
<main class="w-full pt-32 bg-background min-h-screen"><div class="flex flex-col w-full">
<!-- Subtle Ambient Glow -->
<div class="relative w-full max-w-[1440px] mx-auto px-6 lg:px-12 py-10">
<div class="absolute top-12 left-1/4 w-96 h-96 bg-secondary-fixed/20 rounded-full blur-3xl pointer-events-none -z-10"></div>
<div class="absolute bottom-20 right-10 w-[30rem] h-[30rem] bg-surface-dim/40 rounded-full blur-3xl pointer-events-none -z-10"></div>
<!-- Step Header & Guarantees -->
<section class="mb-12">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8">
<div>
<div class="flex items-center gap-3 mb-3">
<span class="font-label-sm text-label-sm uppercase tracking-[0.28em] text-secondary">Adım 05 — Ödeme ve Onay</span>
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Aegean Kasası</span>
</div>
<h1 class="font-display-md text-display-md text-on-surface tracking-tight">
            <span class="italic font-normal">Sığınağınızı</span> Onaylayın ve Güvenceye Alın
</h1>
</div>
<div class="flex items-center gap-3 px-5 py-3 bg-surface-container text-on-surface-variant shadow-sm self-start md:self-auto">
<span class="material-symbols-outlined text-[20px] text-secondary">lock</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface uppercase tracking-[0.2em]">Şifreli Oturum</span>
<span class="font-body-sm text-body-sm text-on-surface-variant text-[11px] leading-tight">TLS 1.3 · 256-Bit Mimari</span>
</div>
</div>
</div>
<!-- Trust Ribbon -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-4 bg-surface-container-low shadow-sm">
<div class="flex items-center gap-3.5 px-4 py-2 bg-surface-container-lowest/60">
<span class="material-symbols-outlined text-secondary text-[22px]" style="font-variation-settings: 'FILL' 1;">verified</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface uppercase tracking-[0.18em]">Doğrudan Garanti</span>
<span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Yayınlanan En İyi Fiyat Garantisi</span>
</div>
</div>
<div class="flex items-center gap-3.5 px-4 py-2 bg-surface-container-lowest/60">
<span class="material-symbols-outlined text-secondary text-[22px]">price_check</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface uppercase tracking-[0.18em]">Kusursuz Şeffaflık</span>
<span class="font-body-sm text-body-sm text-on-surface-variant text-xs">Gizli Ek Ücret veya Tesis Vergisi Yok</span>
</div>
</div>
<div class="flex items-center gap-3.5 px-4 py-2 bg-surface-container-lowest/60">
<span class="material-symbols-outlined text-secondary text-[22px]">event_available</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface uppercase tracking-[0.18em]">Esneklik Süresi</span>
<span class="font-body-sm text-body-sm text-on-surface-variant text-xs">5 Eki, 2025'e kadar Ücretsiz İptal</span>
</div>
</div>
</div>
</section>
<!-- Main Grid Workspace -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
<!-- LEFT COLUMN: Payment Selection & Forms (65% -> 8 Cols) -->
<div class="lg:col-span-8 flex flex-col gap-8">
<!-- Payment Method Tabs -->
<section class="bg-surface-container-lowest p-6 md:p-8 shadow-sm">
<div class="flex items-center justify-between mb-6">
<span class="font-label-sm text-label-sm text-secondary uppercase tracking-[0.24em]">Sığınak Ödeme Yöntemi</span>
<span class="font-body-sm text-body-sm text-outline text-xs">Tercih ettiğiniz işlem protokolünü seçin</span>
</div>
<!-- Radio-Tab Options -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-8" id="payment-method-selector">
<!-- Option 1: Card (Active) -->
<label class="cursor-pointer relative flex flex-col justify-between p-4 bg-surface-container-low transition-all duration-300 hover:bg-surface-container" id="tab-card">
<div class="flex items-center justify-between mb-4">
<span class="font-label-md text-label-md text-on-surface uppercase tracking-[0.16em]">Kredi / Banka Kartı</span>
<span class="w-4 h-4 bg-primary flex items-center justify-center">
<span class="w-1.5 h-1.5 bg-surface-container-lowest"></span>
</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed mb-4">Visa, Mastercard, Amex, UnionPay</p>
<div class="flex items-center gap-2">
<span class="font-label-sm text-[10px] tracking-widest text-outline uppercase">3D Secure 2.0</span>
</div>
</label>
<!-- Option 2: Apple Pay / Google Pay -->
<label class="cursor-pointer relative flex flex-col justify-between p-4 bg-surface hover:bg-surface-container-low transition-all duration-300" id="tab-digital">
<div class="flex items-center justify-between mb-4">
<span class="font-label-md text-label-md text-on-surface uppercase tracking-[0.16em]">Anında Cüzdan</span>
<span class="w-4 h-4 bg-surface-dim flex items-center justify-center"></span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed mb-4">Apple Pay / Google Pay tek dokunuşla biyometrik ödeme</p>
<div class="flex items-center gap-2">
<span class="font-label-sm text-[10px] tracking-widest text-outline uppercase">Biyometrik Onaylı</span>
</div>
</label>
<!-- Option 3: Private Wire -->
<label class="cursor-pointer relative flex flex-col justify-between p-4 bg-surface hover:bg-surface-container-low transition-all duration-300" id="tab-wire">
<div class="flex items-center justify-between mb-4">
<span class="font-label-md text-label-md text-on-surface uppercase tracking-[0.16em]">Özel Havale</span>
<span class="w-4 h-4 bg-surface-dim flex items-center justify-center"></span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed mb-4">₺30.000 üzeri konaklamalar için Concierge Faturası</p>
<div class="flex items-center gap-2">
<span class="font-label-sm text-[10px] tracking-widest text-secondary uppercase">48 Saatlik Sığınak Bekletme</span>
</div>
</label>
</div>
<!-- Card Inputs Form Container -->
<div class="space-y-6">
<!-- Cardholder Name -->
<div class="space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant flex items-center justify-between">
<span>Kart Sahibinin Adı</span>
<span class="text-outline lowercase font-body-sm text-xs">pasaportla eşleşir</span>
</label>
<input class="w-full bg-surface-container-low px-5 py-4 font-body-md text-body-md text-on-surface focus:bg-surface-container focus:outline-none transition-colors" type="text" value="Lady Helena Vance-Cross">
</div>
<!-- Card Number with Automatic Recognition -->
<div class="space-y-2">
<div class="flex items-center justify-between">
<label class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Kart Numarası</label>
<div class="flex items-center gap-2">
<span class="px-2 py-0.5 bg-surface-container text-on-surface font-label-sm text-[10px] tracking-widest uppercase">Mastercard</span>
<span class="px-2 py-0.5 bg-surface-container text-outline font-label-sm text-[10px] tracking-widest uppercase">Amex</span>
<span class="px-2 py-0.5 bg-surface-container text-outline font-label-sm text-[10px] tracking-widest uppercase">Visa</span>
</div>
</div>
<div class="relative">
<input class="w-full bg-surface-container-low px-5 py-4 font-body-md text-body-md text-on-surface tracking-widest focus:bg-surface-container focus:outline-none transition-colors" type="text" value="5412 •••• •••• 8892">
<div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2 pointer-events-none">
<!-- Custom SVG Mastercard Symbol -->
<svg class="w-7 h-5" fill="none" viewbox="0 0 36 24">
<circle cx="13" cy="12" fill="#EB001B" fill-opacity="0.85" r="10"></circle>
<circle cx="23" cy="12" fill="#F79E1B" fill-opacity="0.85" r="10"></circle>
</svg>
<span class="material-symbols-outlined text-[18px] text-secondary">check_circle</span>
</div>
</div>
</div>
<!-- Expiry & CVV Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
<div class="space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Son Kullanma Tarihi</label>
<input class="w-full bg-surface-container-low px-5 py-4 font-body-md text-body-md text-on-surface focus:bg-surface-container focus:outline-none transition-colors" placeholder="MM / YY" type="text" value="09 / 28">
</div>
<div class="space-y-2">
<div class="flex items-center justify-between">
<label class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Güvenlik Kodu (CVV)</label>
<div class="group relative flex items-center cursor-pointer">
<span class="font-label-sm text-secondary text-[11px] underline tracking-widest uppercase">Bu nedir?</span>
<!-- Tooltip Box -->
<div class="absolute right-0 bottom-full mb-2 hidden group-hover:block w-56 p-3 bg-surface-container-highest shadow-xl text-on-surface z-20">
<p class="font-body-sm text-body-sm text-xs leading-normal">
                        Visa/Mastercard'ın arkasında 3 haneli veya American Express kartlarının önünde 4 haneli sayı.
                      </p>
</div>
</div>
</div>
<div class="relative">
<input class="w-full bg-surface-container-low px-5 py-4 font-body-md text-body-md text-on-surface tracking-widest focus:bg-surface-container focus:outline-none transition-colors" maxlength="4" type="password" value="842">
<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline-variant text-[18px]">key</span>
</div>
</div>
</div>
<!-- Billing Address Checkbox -->
<div class="pt-2">
<label class="flex items-center gap-3 cursor-pointer select-none">
<div class="w-4 h-4 bg-primary flex items-center justify-center">
<span class="material-symbols-outlined text-[14px] text-on-primary">check</span>
</div>
<span class="font-body-sm text-body-sm text-on-surface">Fatura adresi Misafir Dosyası ile aynıdır (Mayfair, Londra, UK)</span>
</label>
</div>
</div>
</section>
<!-- Payment Schedule & Settlement Policy -->
<section class="bg-surface-container-low p-6 md:p-8 shadow-sm">
<div class="flex items-center justify-between mb-4">
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">Ödeme Takvimi</span>
<span class="font-label-sm text-label-sm uppercase text-outline">Seçenek Matrisi</span>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<!-- Option A: Reserve with Zero Due Today -->
<label class="p-5 bg-surface-container-lowest/60 hover:bg-surface-container-lowest cursor-pointer transition-all flex flex-col justify-between">
<div>
<div class="flex items-center justify-between mb-2">
<span class="font-label-md text-label-md uppercase tracking-[0.16em] text-on-surface">Bugün Ödenecek</span>
<span class="font-headline-sm text-headline-sm text-secondary">₺0.00</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed">
                  Şu anda sadece kart doğrulama işlemi yapılmaktadır. Rezervasyon toplamı <strong>5 Ekim 2025</strong> tarihinde otomatik olarak işlenecektir.
                </p>
</div>
<div class="mt-4 pt-3 flex items-center gap-2 text-outline">
<span class="w-3 h-3 rounded-full bg-surface-container-highest"></span>
<span class="font-label-sm text-[11px] uppercase tracking-wider">Ertelenmiş Bekletme</span>
</div>
</label>
<!-- Option B: Full Pre-payment (Active) -->
<label class="p-5 bg-surface-container-lowest shadow-sm cursor-pointer transition-all flex flex-col justify-between relative overflow-hidden">
<div class="absolute top-0 right-0 px-3 py-1 bg-secondary text-on-secondary font-label-sm text-[9px] uppercase tracking-widest">
                Tercih Edilen
              </div>
<div>
<div class="flex items-center justify-between mb-2">
<span class="font-label-md text-label-md uppercase tracking-[0.16em] text-on-surface">Anında Garanti</span>
<span class="font-headline-sm text-headline-sm text-on-surface">₺42,350</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed">
                  Anında onay belgesi gönderimi. 5 Ekim 2025 saat 12:00 Aegean saatine kadar %100 tam iade haklarını korur.
                </p>
</div>
<div class="mt-4 pt-3 flex items-center gap-2 text-primary font-medium">
<span class="w-3 h-3 rounded-full bg-secondary"></span>
<span class="font-label-sm text-[11px] uppercase tracking-wider text-secondary">Anında Folyo Fişi</span>
</div>
</label>
</div>
</section>
<!-- Security & Compliance Accordion -->
<section class="space-y-3">
<!-- Item 1: 3D Secure 2.0 -->
<details class="group bg-surface-container-lowest p-5 transition-all shadow-sm open:pb-6" open="">
<summary class="flex items-center justify-between cursor-pointer list-none">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary text-[20px]">shield</span>
<span class="font-label-md text-label-md uppercase tracking-[0.18em] text-on-surface">3D Secure 2.0 Kimlik Doğrulaması</span>
</div>
<span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
</summary>
<div class="pt-4 text-on-surface-variant font-body-sm text-body-sm leading-relaxed max-w-2xl pl-8">
              Kartınızı veren kuruluş tarafından pürüzsüz bir biyometrik veya tek seferlik SMS doğrulama penceresi görüntülenebilir. Bu üst düzey koruma, ne AURA'nın ne de herhangi bir aracının hassas kart şifreleme bilgilerinizi asla saklamamasını sağlar.
            </div>
</details>
<!-- Item 2: Bank-Grade SSL -->
<details class="group bg-surface-container-lowest p-5 transition-all shadow-sm open:pb-6">
<summary class="flex items-center justify-between cursor-pointer list-none">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary text-[20px]">enhanced_encryption</span>
<span class="font-label-md text-label-md uppercase tracking-[0.18em] text-on-surface">Banka Düzeyinde 256-Bit SSL Şifreleme</span>
</div>
<span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
</summary>
<div class="pt-4 text-on-surface-variant font-body-sm text-body-sm leading-relaxed max-w-2xl pl-8">
              Tüm iletişim, PCI-DSS Seviye 1 spesifikasyonları altında sertifikalandırılmış özel fiber kanallar üzerinden yönlendirilir. İşleminiz doğrudan İsviçre merkezli üye işyeri ortağımıza iletilir.
            </div>
</details>
<!-- Item 3: Cancellation Guarantee Details -->
<details class="group bg-surface-container-lowest p-5 transition-all shadow-sm open:pb-6">
<summary class="flex items-center justify-between cursor-pointer list-none">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary text-[20px]">history_edu</span>
<span class="font-label-md text-label-md uppercase tracking-[0.18em] text-on-surface">Aegean Serenity İptal Tüzüğü</span>
</div>
<span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
</summary>
<div class="pt-4 text-on-surface-variant font-body-sm text-body-sm leading-relaxed max-w-2xl pl-8">
              <strong>5 Ekim 2025 saat 12:00 (GMT+3)</strong>'e kadar %100 tam iade ile iptal edebilirsiniz. Herhangi bir yönetim ücreti veya iptal cezası alınmayacaktır. Bu tarihten sonraki iptallerde sadece ilk gece depozitosu yanacaktır.
            </div>
</details>
</section>
<!-- Final Action CTA Block -->
<section class="p-6 md:p-8 bg-surface-container flex flex-col gap-4">
<button class="w-full py-5 bg-primary text-on-primary hover:bg-secondary transition-all duration-300 flex items-center justify-center gap-3 font-label-md text-label-md uppercase tracking-[0.24em] shadow-md group" type="button">
<span class="material-symbols-outlined text-[20px] transition-transform group-hover:scale-110">lock</span>
<span>Rezervasyonu Tamamla — ₺42,350</span>
</button>
<div class="flex items-center justify-center gap-2 text-center text-outline">
<span class="font-body-sm text-[12px] leading-tight max-w-xl">
              Rezervasyonunuzu onaylayarak, ücretlendirmeye yetki veriyor ve <a class="underline text-on-surface hover:text-secondary" href="#">AURA Sanctum Konaklama Şartları</a>'nı ile <a class="underline text-on-surface hover:text-secondary" href="#">Aegean Çevre Tüzüğü</a>'nü onaylıyorsunuz.
            </span>
</div>
</section>
</div>
<!-- RIGHT COLUMN: Sticky Comprehensive Folio Ledger (35% -> 4 Cols) -->
<div class="lg:col-span-4 sticky top-36 flex flex-col gap-6">
<!-- Folio Card Container -->
<div class="bg-surface-container-lowest p-6 shadow-sm">
<!-- Folio Header -->
<div class="flex items-center justify-between pb-5">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.22em] text-secondary">Folyo Özeti</span>
<p class="font-headline-sm text-headline-sm text-on-surface">Konaklama Manifestosu</p>
</div>
<span class="px-2.5 py-1 bg-surface-container text-on-surface-variant font-label-sm text-[10px] tracking-widest uppercase">
              Onaylanmış Taslak
            </span>
</div>
<!-- Suite Preview Mosaic -->
<div class="relative overflow-hidden mb-5">
<img class="w-full h-44 object-cover object-center" data-alt="A sun-drenched minimalist sanctuary bedroom overlooking the Aegean Sea in Bodrum Turkey. Natural travertine walls, unbleached linen bedding, warm wood fixtures, deep shadows, cinematic warm ambient afternoon light, architectural photography." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwrNjB0jt3rXmDOFjXTwESxMVW8KTElHp-JjiWrIC4B0JWCW7mAXM039YcwPrgqpr-RJ-DIGkt7A2IqQL0RS1_W-0Zb6krNmU86D7RDdvfSIe72U84nG9NseaS7TPErK16nkvQ-PQE4HMfkX7nI-LuGK901gjInbviyTnralzWXvwuiS-opQXzAHkh5LloN3KCDTJDwpO8aXb6rGnNcHO6aPMy1gkxaIaEq3234hJ9RcQgGmwk4t0VLw">
<div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent"></div>
<div class="absolute bottom-3 left-3 right-3 flex items-end justify-between text-on-primary">
<div>
<p class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-surface-variant">Seçilen Sığınak</p>
<h3 class="font-headline-sm text-headline-sm text-on-primary">Deluxe Deniz Manzaralı Süit</h3>
</div>
<span class="font-label-sm text-label-sm uppercase text-surface tracking-widest">Villa IV</span>
</div>
</div>
<!-- Schedule & Occupancy Specs -->
<div class="grid grid-cols-2 gap-3 p-3 bg-surface-container-low mb-6">
<div class="flex flex-col">
<span class="font-label-sm text-[10px] uppercase tracking-wider text-outline">Giriş ve Çıkış</span>
<span class="font-body-sm text-body-sm font-medium text-on-surface">12 Eki – 17 Eki, 2025</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">5 Kesintisiz Gece</span>
</div>
<div class="flex flex-col">
<span class="font-label-sm text-[10px] uppercase tracking-wider text-outline">Sığınak Misafirleri</span>
<span class="font-body-sm text-body-sm font-medium text-on-surface">2 Yetişkin</span>
<span class="font-body-sm text-[11px] text-on-surface-variant">King Horizon Yatak</span>
</div>
</div>
<!-- Itemized Breakdown -->
<div class="space-y-3.5 pb-6">
<div class="flex items-baseline justify-between font-body-sm text-body-sm">
<span class="text-on-surface-variant">Sığınak Ücreti (5 gece × ₺5,750)</span>
<span class="text-on-surface font-medium">₺28,750</span>
</div>
<div class="flex items-baseline justify-between font-body-sm text-body-sm">
<span class="text-on-surface-variant">Maybach Şoför (Gidiş-Dönüş BJV)</span>
<span class="text-on-surface font-medium">₺3,800</span>
</div>
<div class="flex items-baseline justify-between font-body-sm text-body-sm">
<span class="text-on-surface-variant">Mum Işığında Uçurum Kenarı Tadımı</span>
<span class="text-on-surface font-medium">₺9,800</span>
</div>
<div class="flex items-baseline justify-between font-body-sm text-body-sm">
<span class="text-on-surface-variant flex items-center gap-1.5">
                Geçerli KDV ve Aegean Vergileri
                <span class="material-symbols-outlined text-[14px] text-outline">info</span>
</span>
<span class="text-secondary font-medium">Dahil (₺3,388)</span>
</div>
</div>
<!-- Total Calculation -->
<div class="pt-5 bg-surface-container-low -mx-6 -mb-6 p-6">
<div class="flex items-baseline justify-between mb-1">
<span class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface">Ödenecek Toplam Tutar</span>
<span class="font-headline-md text-headline-md text-on-surface font-semibold">₺42,350</span>
</div>
<div class="flex justify-between items-center text-outline text-xs font-body-sm">
<span>Türk Lirası (TRY) üzerinden tahsil edilir</span>
<span>yakl. €1,145 / $1,245 USD</span>
</div>
</div>
</div>
<!-- Guest Manifest Card -->
<div class="bg-surface-container-lowest p-6 shadow-sm">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined text-secondary text-[18px]">badge</span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface">Kayıtlı Misafir</span>
</div>
<div class="space-y-1.5 font-body-sm text-body-sm">
<p class="font-medium text-on-surface">Lady Helena Vance-Cross</p>
<p class="text-on-surface-variant">+44 7700 900077 · helena.vance@cross-estates.co.uk</p>
<div class="pt-2 flex items-center gap-2">
<span class="px-2 py-0.5 bg-surface-container text-on-surface-variant text-[11px] font-label-sm uppercase">Tahmini Varış: 14:00 - 16:00</span>
<span class="px-2 py-0.5 bg-surface-container text-on-surface-variant text-[11px] font-label-sm uppercase">Geç Çıkış Önceliği</span>
</div>
</div>
</div>
<!-- 24/7 Dedicated Butler Desk -->
<div class="p-6 bg-surface-container-high shadow-sm">
<div class="flex items-start gap-4">
<div class="w-10 h-10 bg-primary text-on-primary flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-[20px]">room_service</span>
</div>
<div class="space-y-1">
<span class="font-label-sm text-label-sm uppercase tracking-[0.22em] text-secondary">Özel Concierge</span>
<h4 class="font-headline-sm text-headline-sm text-on-surface">Özel Uşak Masası</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed">
                Özel beslenme düzenlemeleri, yat bağlama veya helikopter transferleri için 24 saat hizmetinizdedir.
              </p>
<div class="pt-2">
<a class="inline-flex items-center gap-2 font-label-md text-label-md text-on-surface hover:text-secondary uppercase tracking-[0.16em] transition-colors" href="tel:+902523114000">
<span class="material-symbols-outlined text-[16px]">call</span>
                  +90 252 311 4000
                </a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Inline Tab & Interaction Microscript -->

</div></main>
@push('scripts')
<script>
    (function initPaymentTabs() {
      const tabs = [
        { id: 'tab-card', active: true },
        { id: 'tab-digital', active: false },
        { id: 'tab-wire', active: false }
      ];

      tabs.forEach(tab => {
        const el = document.getElementById(tab.id);
        if (!el) return;
        el.addEventListener('click', () => {
          tabs.forEach(t => {
            const node = document.getElementById(t.id);
            if (!node) return;
            const marker = node.querySelector('span:nth-child(2)');
            if (t.id === tab.id) {
              node.classList.remove('bg-surface');
              node.classList.add('bg-surface-container-low');
              if (marker) {
                marker.className = 'w-4 h-4 bg-primary flex items-center justify-center';
                marker.innerHTML = '<span class="w-1.5 h-1.5 bg-surface-container-lowest">';
              }
            } else {
              node.classList.remove('bg-surface-container-low');
              node.classList.add('bg-surface');
              if (marker) {
                marker.className = 'w-4 h-4 bg-surface-dim flex items-center justify-center';
                marker.innerHTML = '';
              }
            }
          });
        });
      });
    })();
  </script>

@endpush
</x-layouts.reservation>
