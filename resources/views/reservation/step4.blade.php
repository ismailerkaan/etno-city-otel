<x-layouts.reservation>
<main class="w-full pt-32 bg-background min-h-screen"><div class="flex flex-col w-full">
<div class="w-full max-w-[1440px] mx-auto px-6 lg:px-12 py-10 lg:py-16">
<!-- Top Editorial Header & Progress Signal -->
<header class="mb-12 lg:mb-16">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8">
<div class="space-y-3 max-w-3xl">
<div class="flex items-center gap-3">
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">Adım 04 / 05</span>
<span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-on-surface-variant">Gizli Rezervasyon</span>
</div>
<h1 class="font-display-md text-display-md text-on-surface tracking-tight">Misafir Bilgileri ve Varış Öncesi Tercihler</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl">
            Hesap kaydı gerekmez. Kıyıya varışınızdan ayrılışınıza kadar acelesiz özel bir bakım sağlayarak rezervasyonunuzu doğrudan özel concierge masamızla tamamlayın.
          </p>
</div>
<!-- Reassurance Seal -->
<div class="flex items-center gap-3.5 bg-surface-container-low px-5 py-3.5 flex-shrink-0">
<span class="material-symbols-outlined text-secondary text-2xl">verified_user</span>
<div class="flex flex-col">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-on-surface font-semibold">Doğrudan Sığınak Garantisi</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Gizli Folyo • Şifreli Kanal</span>
</div>
</div>
</div>
</header>
<!-- Main Asymmetric Grid Layout (68% Form / 32% Sticky Summary) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">
<!-- Left Column: Editorial Structured Form (8 cols) -->
<section class="lg:col-span-8 space-y-12">
<!-- SECTION 1: Lead Guest Information -->
<div class="bg-surface-container-lowest p-8 lg:p-12 shadow-sm space-y-8">
<div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 pb-2">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary block mb-1">Bölüm I</span>
<h2 class="font-headline-md text-headline-md text-on-surface">Ana Misafir Bilgileri</h2>
</div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.15em] text-outline text-right">Kayıtlı Birincil Sakin</span>
</div>
<!-- Form Fields Grid -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
<!-- Salutation / Title -->
<div class="md:col-span-3 space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="guest-title">Hitap</label>
<div class="relative">
<select class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors appearance-none cursor-pointer" id="guest-title">
<option value="mr">Bay</option>
<option value="mrs">Bayan</option>
<option value="ms">Bayan</option>
<option value="dr">Dr.</option>
<option value="none">Belirtmek istemiyorum</option>
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">expand_more</span>
</div>
</div>
<!-- First Name -->
<div class="md:col-span-4 space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="first-name">Ad(lar) *</label>
<input class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors" id="first-name" placeholder="Julian" required="" type="text">
</div>
<!-- Last Name -->
<div class="md:col-span-5 space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="last-name">Soyad *</label>
<input class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors" id="last-name" placeholder="Vane-Tempest" required="" type="text">
</div>
<!-- Email Address -->
<div class="md:col-span-7 space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="email">Gizli İletişim E-postası *</label>
<span class="font-label-sm text-label-sm text-secondary tracking-normal">Doğrudan şifreli fiş</span>
</div>
<input class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors" id="email" placeholder="julian.vane@residence.ch" required="" type="email">
</div>
<!-- Country of Residence -->
<div class="md:col-span-5 space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="residence">İkamet Edilen Ülke *</label>
<div class="relative">
<select class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors appearance-none cursor-pointer" id="residence">
<option selected value="CH">İsviçre</option>
<option value="UK">Birleşik Krallık</option>
<option value="DE">Almanya</option>
<option value="FR">Fransa</option>
<option value="US">Amerika Birleşik Devletleri</option>
<option value="TR">Türkiye</option>
<option value="UAE">Birleşik Arap Emirlikleri</option>
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">expand_more</span>
</div>
</div>
<!-- Telephone & WhatsApp Support -->
<div class="md:col-span-12 space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="phone-number">Cep Telefonu (Ülke Kodu İle) *</label>
<div class="flex flex-col sm:flex-row gap-3">
<div class="relative w-full sm:w-56 flex-shrink-0">
<select class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors appearance-none cursor-pointer" id="country-code">
<option value="+41">+41 (İsviçre)</option>
<option selected value="+44">+44 (Birleşik Krallık)</option>
<option value="+49">+49 (Almanya)</option>
<option value="+33">+33 (Fransa)</option>
<option value="+1">+1 (Amerika Birleşik Devletleri)</option>
<option value="+90">+90 (Türkiye)</option>
<option value="+971">+971 (BAE)</option>
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">expand_more</span>
</div>
<div class="relative flex-1">
<input class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors" id="phone-number" placeholder="7911 204932" required="" type="tel">
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant pt-1">
                Yalnızca acil uçuş gecikmesi koordinasyonları, helikopter pisti transferleri veya doğrudan gizli WhatsApp varış bildirimleri için kullanılır.
              </p>
</div>
</div>
</div>
<!-- SECTION 2: Sanctuary & Stay Personalization -->
<div class="bg-surface-container-lowest p-8 lg:p-12 shadow-sm space-y-10">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary block mb-1">Bölüm II</span>
<h2 class="font-headline-md text-headline-md text-on-surface">Sığınak Kişiselleştirme ve Varış</h2>
<p class="font-body-md text-body-md text-on-surface-variant mt-1">Görevlilerimiz limana veya yola varmadan önce süitinizin mikro iklimini, aromaterapisini ve mutfak olanaklarını kişiselleştirir.</p>
</div>
<!-- Arrival Logistics -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-2">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="arrival-time">Tahmini Varış Aralığı</label>
<div class="relative">
<select class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors appearance-none cursor-pointer" id="arrival-time">
<option value="early">Sabah Erken (08:00 – 11:00, Süit müsaitliğe bağlıdır)</option>
<option value="noon">Öğle (11:00 – 14:00)</option>
<option selected value="standard">Sığınak Girişi (14:00 – 17:00 Öğleden Sonra)</option>
<option value="evening">Gün Batımı / Alacakaranlık (17:00 – 21:00)</option>
<option value="late">Gece Varışı (21:00 ve sonrası)</option>
</select>
<span class="material-symbols-outlined pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-outline text-lg">expand_more</span>
</div>
</div>
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="flight-details">Uçuş / Yat Kodu</label>
<span class="font-label-sm text-label-sm text-outline">İsteğe Bağlı</span>
</div>
<input class="w-full bg-surface-container-low px-4 py-3.5 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors" id="flight-details" placeholder="e.g. BA 672 arriving BJV at 13:45 / Motor Yacht Althea" type="text">
</div>
</div>
<!-- Pillow Menu Selection Pills -->
<div class="space-y-4 pt-2">
<div class="flex items-baseline justify-between">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block">Özel Yastık ve Yatak Menüsü</label>
<span class="font-body-sm text-body-sm text-secondary">Süit İçi Ücretsiz Seçenek</span>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="pillow-options">
<label class="relative flex flex-col p-4 bg-surface-container-low cursor-pointer transition-all hover:bg-surface-container group">
<input checked class="peer sr-only" name="pillow_menu" type="radio" value="down">
<div class="flex items-center justify-between mb-2">
<span class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Kaz Tüyü</span>
<div class="w-4 h-4 bg-surface-container-highest peer-checked:bg-primary flex items-center justify-center transition-colors">
<span class="material-symbols-outlined text-xs text-on-primary opacity-0 peer-checked:opacity-100">check</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Ham Mısır patiska pamuğu ile kaplanmış hipoalerjenik 800 dolumlu Macar beyaz kaz tüyü.</p>
<div class="absolute inset-0 border-2 border-transparent peer-checked:border-secondary pointer-events-none transition-colors"></div>
</label>
<label class="relative flex flex-col p-4 bg-surface-container-low cursor-pointer transition-all hover:bg-surface-container group">
<input class="peer sr-only" name="pillow_menu" type="radio" value="memory">
<div class="flex items-center justify-between mb-2">
<span class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Ergonomik Köpük</span>
<div class="w-4 h-4 bg-surface-container-highest peer-checked:bg-primary flex items-center justify-center transition-colors">
<span class="material-symbols-outlined text-xs text-on-primary opacity-0 peer-checked:opacity-100">check</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Hizalanmış servikal dekompresyon için doğal kömür aşılanmış bio-visko kontur çekirdeği.</p>
<div class="absolute inset-0 border-2 border-transparent peer-checked:border-secondary pointer-events-none transition-colors"></div>
</label>
<label class="relative flex flex-col p-4 bg-surface-container-low cursor-pointer transition-all hover:bg-surface-container group">
<input class="peer sr-only" name="pillow_menu" type="radio" value="aromatherapy">
<div class="flex items-center justify-between mb-2">
<span class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Lavanta Botanik</span>
<div class="w-4 h-4 bg-surface-container-highest peer-checked:bg-primary flex items-center justify-center transition-colors">
<span class="material-symbols-outlined text-xs text-on-primary opacity-0 peer-checked:opacity-100">check</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant">Sakinleştirici gece aromaterapisi için yabani Aegean lavantası ve taşta öğütülmüş kavuzlu buğday kabuğu.</p>
<div class="absolute inset-0 border-2 border-transparent peer-checked:border-secondary pointer-events-none transition-colors"></div>
</label>
</div>
</div>
<!-- Dietary & Nourishment Checklist -->
<div class="space-y-4 pt-2">
<div>
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block">Beslenme ve Mutfak Profili</label>
<p class="font-body-sm text-body-sm text-on-surface-variant">Hoş geldin iksirlerine, süit içi olanaklara ve uçurum kenarındaki yemek menülerimize gizlice uygulanır.</p>
</div>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
<label class="flex items-center gap-3 p-3 bg-surface-container-low hover:bg-surface-container transition-colors cursor-pointer select-none">
<input class="w-4 h-4 rounded-none accent-primary cursor-pointer" name="dietary" type="checkbox" value="vegetarian">
<span class="font-body-sm text-body-sm text-on-surface">Vejetaryen</span>
</label>
<label class="flex items-center gap-3 p-3 bg-surface-container-low hover:bg-surface-container transition-colors cursor-pointer select-none">
<input checked class="w-4 h-4 rounded-none accent-primary cursor-pointer" name="dietary" type="checkbox" value="pescatarian">
<span class="font-body-sm text-body-sm text-on-surface">Pesketaryen</span>
</label>
<label class="flex items-center gap-3 p-3 bg-surface-container-low hover:bg-surface-container transition-colors cursor-pointer select-none">
<input class="w-4 h-4 rounded-none accent-primary cursor-pointer" name="dietary" type="checkbox" value="gluten-free">
<span class="font-body-sm text-body-sm text-on-surface">Glütensiz</span>
</label>
<label class="flex items-center gap-3 p-3 bg-surface-container-low hover:bg-surface-container transition-colors cursor-pointer select-none">
<input class="w-4 h-4 rounded-none accent-primary cursor-pointer" name="dietary" type="checkbox" value="alcohol-free">
<span class="font-body-sm text-body-sm text-on-surface">Alkolsuz</span>
</label>
<label class="flex items-center gap-3 p-3 bg-surface-container-low hover:bg-surface-container transition-colors cursor-pointer select-none">
<input class="w-4 h-4 rounded-none accent-primary cursor-pointer" name="dietary" type="checkbox" value="nut-allergies">
<span class="font-body-sm text-body-sm text-on-surface">Kuruyemiş Alerjisi</span>
</label>
</div>
</div>
<!-- Special Requests & Celebrations -->
<div class="space-y-2 pt-2">
<div class="flex justify-between items-center">
<label class="font-label-sm text-label-sm uppercase tracking-[0.18em] text-on-surface-variant block" for="special-requests">Sığınak Nüansları ve Etkinlikler</label>
<span class="font-label-sm text-label-sm text-outline">Maître için isteğe bağlı notlar</span>
</div>
<textarea class="w-full bg-surface-container-low p-4 text-on-surface placeholder:text-outline/60 font-body-md text-body-md focus:bg-surface-container focus:outline-none transition-colors resize-none" id="special-requests" placeholder="e.g., Celebrating our 10th anniversary; kindly allocate a suite on the higher cliff promontory with uninterrupted horizon view. Prefer natural unheated spring water in carafes." rows="4"></textarea>
</div>
</div>
<!-- SECTION 3: Privacy & Discrete Dispatch -->
<div class="bg-surface-container-lowest p-8 lg:p-12 shadow-sm space-y-6">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary block mb-1">Bölüm III</span>
<h2 class="font-headline-md text-headline-md text-on-surface">Gizlilik ve Protokol</h2>
</div>
<div class="space-y-4">
<!-- WhatsApp Consent -->
<label class="flex items-start gap-3.5 p-4 bg-surface-container-low cursor-pointer hover:bg-surface-container transition-colors">
<input checked class="mt-1 w-4 h-4 rounded-none accent-primary flex-shrink-0 cursor-pointer" type="checkbox">
<div class="space-y-1">
<span class="font-title-md text-title-md text-on-surface block">Gizli WhatsApp Şoför Bağlantısı</span>
<p class="font-body-sm text-body-sm text-on-surface-variant">Terminal varışları ve transfer koordinasyonu ile ilgili göze batmayan canlı güncellemeler sağlamak için concierge'imize ve atanan Mercedes-Maybach özel şoförümüze yetki verin.</p>
</div>
</label>
<!-- Terms & Cancellation Agreement -->
<label class="flex items-start gap-3.5 p-4 bg-surface-container-low cursor-pointer hover:bg-surface-container transition-colors">
<input class="mt-1 w-4 h-4 rounded-none accent-primary flex-shrink-0 cursor-pointer" required="" type="checkbox">
<div class="space-y-1">
<span class="font-title-md text-title-md text-on-surface block">Sığınak Konaklama Şartları ve Doğrudan Garanti *</span>
<p class="font-body-sm text-body-sm text-on-surface-variant"><a class="text-secondary underline hover:text-on-surface" href="#">Sığınak Sükunet Kuralları</a>'na uyacağımı onaylıyor ve 5 Ekim 2025 tarihine kadar geçerli olan %100 Ücretsiz İptal Politikasını kabul ediyorum.</p>
</div>
</label>
</div>
</div>
</section>
<!-- Right Column: Sticky Reservation Ledger (4 cols) -->
<aside class="lg:col-span-4 sticky top-36 space-y-6">
<div class="bg-surface-container-lowest p-8 shadow-sm space-y-6">
<!-- Folio Header -->
<div class="flex items-baseline justify-between pb-4 border-b border-surface-container-high">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">Rezervasyon Folyosu</span>
<h3 class="font-headline-sm text-headline-sm text-on-surface mt-0.5">Konaklama Mimarisi</h3>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Ref: #AR-8831</span>
</div>
<!-- Suite Preview Card -->
<div class="space-y-4">
<div class="relative overflow-hidden group">
<img class="w-full h-44 object-cover transition-transform duration-700 group-hover:scale-105" data-alt="An expansive luxury minimalist sanctuary suite overlooking the turquoise Aegean Sea with warm limestone floors, sheer linen drapes moving gently in the sea breeze, bespoke dark wood low platform bed, and natural morning sunlight." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDB4Hjk8dDdcmjnuIbZ6QpXVwIoFQy_w2iD6jrMk805Dsq16ZRf5cA4H8v0mUT-NFbBZpMOmMcpPYo0SADx2zBqaoyoLstjgzSRGCnv5yZ00dlUiOm9aSZDYyQQTsQhHgR_61gZN8f6C_dYmkpoR2RwUAyIeUCR6vAEWcbg-3hNcpZv9pD74xDsM5edGasFUsy4HUQgtDJYq7Dg7bY4Qs5y3WvOA7rt2JB8dLlFN_m5bi8WpMPrbBZw3g">
<div class="absolute top-3 left-3 bg-primary text-on-primary px-3 py-1 font-label-sm text-label-sm uppercase tracking-[0.18em]">
                Okyanus Cephesi
              </div>
</div>
<div>
<div class="flex items-baseline justify-between">
<h4 class="font-headline-sm text-headline-sm text-on-surface">Deluxe Deniz Manzaralı Süit</h4>
<span class="font-body-md text-body-md font-medium text-on-surface">₺28,750</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">5 Gece • 2 Yetişkin • 12 Eki – 17 Eki, 2025</p>
<div class="mt-2 flex items-center gap-2 text-secondary font-label-sm text-label-sm uppercase tracking-wider">
<span class="material-symbols-outlined text-base">waves</span>
<span>Engelsiz Uçurum Manzarası • 92 m²</span>
</div>
</div>
</div>
<!-- Curated Extras Itemization -->
<div class="space-y-3 pt-4 border-t border-surface-container-high">
<div class="flex justify-between items-baseline">
<span class="font-label-sm text-label-sm uppercase tracking-[0.16em] text-on-surface">Özel Ekstralar (2 Seçildi)</span>
<span class="font-body-md text-body-md font-medium text-on-surface">₺13,600</span>
</div>
<div class="space-y-2.5 bg-surface-container-low p-3.5">
<div class="flex items-start justify-between gap-4 text-on-surface-variant font-body-sm text-body-sm">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm text-secondary">directions_car</span>
<span>Maybach Özel Havalimanı Şoförü</span>
</div>
<span class="font-medium text-on-surface whitespace-nowrap">₺5,800</span>
</div>
<div class="flex items-start justify-between gap-4 text-on-surface-variant font-body-sm text-body-sm">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-sm text-secondary">candle</span>
<span>Uçurum Kenarı Epilog 5 Aşamalı Akşam Yemeği</span>
</div>
<span class="font-medium text-on-surface whitespace-nowrap">₺7,800</span>
</div>
</div>
</div>
<!-- Total Due & Guarantee Notice -->
<div class="pt-4 border-t border-surface-container-high space-y-4">
<div class="flex items-baseline justify-between">
<div>
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">Toplam Sığınak Tutarı</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Tüm Aegean yerel vergileri ve hizmetleri dahildir</span>
</div>
<div class="text-right">
<span class="font-headline-md text-headline-md font-medium text-on-surface tracking-tight block">₺42,350</span>
<span class="font-label-sm text-label-sm text-outline">~ €1,145 EUR</span>
</div>
</div>
<!-- Free Cancellation Badge -->
<div class="bg-secondary-container/40 p-3.5 flex items-start gap-3">
<span class="material-symbols-outlined text-on-secondary-container text-lg flex-shrink-0 mt-0.5">event_available</span>
<p class="font-body-sm text-body-sm text-on-secondary-container">
<strong class="font-semibold">Esnek Güvence:</strong> <strong>5 Ekim 2025</strong> (23:59 EEST) tarihine kadar %100 ücretsiz iptal garantisi.
              </p>
</div>
</div>
<!-- Action CTA Group -->
<div class="pt-2 space-y-3">
<a class="w-full py-4 bg-primary text-on-primary hover:bg-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.22em] text-center flex items-center justify-center gap-3 group" data-path="payment" href="#">
<span>Güvenli Ödemeye İlerle</span>
<span class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
</a>
<a class="w-full py-3 bg-transparent text-on-surface-variant hover:text-on-surface font-label-md text-label-md uppercase tracking-[0.18em] text-center flex items-center justify-center gap-2 transition-colors" data-path="curated-extras" href="#">
<span class="material-symbols-outlined text-sm">arrow_back</span>
<span>Seçili Ekstraları Düzenle</span>
</a>
</div>
<!-- Trust & Encryption Safeguards -->
<div class="pt-4 border-t border-surface-container-high space-y-2.5">
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
<span class="material-symbols-outlined text-base text-secondary">lock</span>
<span>256-Bit Banka Düzeyinde SSL Şifreleme</span>
</div>
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">
<span class="material-symbols-outlined text-base text-secondary">shield</span>
<span>Kişisel Veriler GDPR Kapsamında Gizlilikle İşlenir</span>
</div>
</div>
</div>
<!-- Concierge Direct Helpline Box -->
<div class="bg-surface-container-low p-6 flex items-start gap-4">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center flex-shrink-0 text-secondary">
<span class="material-symbols-outlined text-xl">concierge</span>
</div>
<div class="space-y-1">
<span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary font-semibold block">Kişisel Yardıma Mı İhtiyacınız Var?</span>
<p class="font-body-sm text-body-sm text-on-surface-variant">Özel beslenme veya özel ulaşım taleplerini karşılamak için Baş Concierge'imiz 24 saat hizmetinizdedir.</p>
<a class="inline-block pt-1 font-body-sm text-body-sm text-on-surface font-medium hover:text-secondary transition-colors" href="tel:+902523114000">+90 252 311 4000</a>
</div>
</div>
</aside>
</div>
</div>
</div>
</main>
@push('scripts')
<script>
  // Simple micro-interaction for visual feedback on pillow menu
  document.querySelectorAll('input[name="pillow_menu"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
      document.querySelectorAll('#pillow-options label').forEach(label => {
        label.classList.remove('bg-surface-container');
      });
      if(e.target.checked) {
        e.target.closest('label').classList.add('bg-surface-container');
      }
    });
  });
</script>

@endpush
</x-layouts.reservation>
