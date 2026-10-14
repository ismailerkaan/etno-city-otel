<x-layouts.app :site-setting="$siteSetting" :title="(app()->isLocale('en') ? 'Contact & Concierge Desk' : 'İletişim & Rezervasyon Masası') . ' | ' . ($siteSetting?->hotel_name ?? 'EtnoCity Otel')">
    <div class="flex flex-col w-full">
        <!-- 1. HERO / BANNER -->
        <section class="relative w-full pt-32 pb-16 bg-surface-container-low border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-4">
                {{-- Breadcrumbs --}}
                <div class="flex items-center gap-2 font-label-sm text-[11px] uppercase tracking-[0.2em] text-secondary">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">{{ app()->isLocale('en') ? 'Home' : 'Anasayfa' }}</a>
                    <span class="text-outline/40">/</span>
                    <span class="text-on-surface">{{ app()->isLocale('en') ? 'Contact' : 'İletişim' }}</span>
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pt-2">
                    <div class="space-y-3 max-w-3xl">
                        <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.28em] block">
                            {{ $siteSetting?->translated('contact_eyebrow') ?: (app()->isLocale('en') ? 'Contact & Reservations' : 'İletişim & Rezervasyon') }}
                        </span>
                        <h1 class="font-display-lg text-headline-lg lg:text-display-md text-on-surface font-normal">
                            {{ $siteSetting?->translated('contact_title') ?: (app()->isLocale('en') ? 'Here to Help With Your Stay' : 'Konaklamanız İçin Buradayız') }}
                        </h1>
                        <p class="font-body-md text-body-md text-on-surface-variant font-light leading-relaxed">
                            {{ $siteSetting?->translated('contact_subtitle') ?: (app()->isLocale('en')
                                ? 'Contact our team for reservations, room preferences, transport information and any questions about your stay.'
                                : 'Rezervasyon, oda tercihleri, ulaşım ve konaklamanıza ilişkin tüm sorularınız için ekibimize ulaşabilirsiniz.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 bg-surface-container px-5 py-3 border border-surface-container-high shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-secondary text-[26px]">support_agent</span>
                        <div>
                            <span class="font-label-sm text-[11px] uppercase tracking-wider text-secondary block font-semibold">
                                {{ $siteSetting?->translated('contact_concierge_hours') ?: (app()->isLocale('en') ? '24/7 Front Desk Support' : '7/24 Resepsiyon Desteği') }}
                            </span>
                            <span class="font-body-sm text-xs text-on-surface-variant">{{ $siteSetting?->address ?: (app()->isLocale('en') ? 'Central Location' : 'Merkezi Konum') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. CONTACT INFO CARDS -->
        <section class="w-full py-12 lg:py-16 bg-surface border-b border-surface-container">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Card 1: Phone & Concierge --}}
                    <div class="bg-surface-container-low p-8 border border-surface-container hover:border-secondary/40 transition-all duration-300 flex flex-col justify-between group shadow-sm">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-surface flex items-center justify-center border border-surface-container-high text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                                <span class="material-symbols-outlined text-[26px]">call</span>
                            </div>
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">{{ app()->isLocale('en') ? 'Call Us' : 'Bizi Arayın' }}</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ app()->isLocale('en') ? 'Concierge & Reservations' : 'Concierge & Rezervasyon' }}</h3>
                            <div class="space-y-1 pt-1 font-body-md text-on-surface-variant">
                                @if ($siteSetting?->phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->phone) }}" class="block font-medium text-on-surface hover:text-secondary transition-colors">
                                        {{ $siteSetting->phone }}
                                    </a>
                                @endif
                                @if ($siteSetting?->contact_phone_concierge)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $siteSetting->contact_phone_concierge) }}" class="block text-sm hover:text-secondary transition-colors">
                                        {{ $siteSetting->contact_phone_concierge }} ({{ app()->isLocale('en') ? 'VIP Desk' : 'VIP Masa' }})
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if ($siteSetting?->whatsapp_url)
                            <div class="pt-6 mt-6 border-t border-surface-container-high/60">
                                <a href="{{ $siteSetting->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 font-label-sm text-label-sm uppercase tracking-wider text-secondary hover:text-primary transition-colors">
                                    <span>{{ app()->isLocale('en') ? 'Direct WhatsApp Chat' : 'WhatsApp ile Görüşün' }}</span>
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Card 2: Email & Inquiries --}}
                    <div class="bg-surface-container-low p-8 border border-surface-container hover:border-secondary/40 transition-all duration-300 flex flex-col justify-between group shadow-sm">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-surface flex items-center justify-center border border-surface-container-high text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                                <span class="material-symbols-outlined text-[26px]">mail</span>
                            </div>
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">{{ app()->isLocale('en') ? 'Email' : 'E-posta' }}</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ app()->isLocale('en') ? 'Email Inquiries' : 'E-Posta Masası' }}</h3>
                            <div class="space-y-1 pt-1 font-body-md text-on-surface-variant">
                                @if ($siteSetting?->email)
                                    <a href="mailto:{{ $siteSetting->email }}" class="block font-medium text-on-surface hover:text-secondary transition-colors">
                                        {{ $siteSetting->email }}
                                    </a>
                                @endif
                                @if ($siteSetting?->contact_email_reservations)
                                    <a href="mailto:{{ $siteSetting->contact_email_reservations }}" class="block text-sm hover:text-secondary transition-colors">
                                        {{ $siteSetting->contact_email_reservations }}
                                    </a>
                                @endif
                                <p class="text-xs text-outline pt-1">{{ app()->isLocale('en') ? 'Response guaranteed within 60 minutes.' : 'Talepleriniz ortalama 1 saat içinde yanıtlanır.' }}</p>
                            </div>
                        </div>
                        <div class="pt-6 mt-6 border-t border-surface-container-high/60">
                            <a href="#contactForm" class="inline-flex items-center gap-2 font-label-sm text-label-sm uppercase tracking-wider text-secondary hover:text-primary transition-colors">
                                <span>{{ app()->isLocale('en') ? 'Submit Inquiry Form' : 'Talep Formunu Doldurun' }}</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                            </a>
                        </div>
                    </div>

                    {{-- Card 3: Location & Coordinates --}}
                    <div class="bg-surface-container-low p-8 border border-surface-container hover:border-secondary/40 transition-all duration-300 flex flex-col justify-between group shadow-sm">
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-surface flex items-center justify-center border border-surface-container-high text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                                <span class="material-symbols-outlined text-[26px]">pin_drop</span>
                            </div>
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] text-secondary block">{{ app()->isLocale('en') ? 'Location' : 'Konum' }}</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface">{{ app()->isLocale('en') ? 'Address & Transport' : 'Adres & Ulaşım' }}</h3>
                            <div class="space-y-1 pt-1 font-body-md text-on-surface-variant">
                                <p class="text-on-surface font-medium">{{ $siteSetting?->address ?? (app()->isLocale('en') ? 'Central Location' : 'Merkezi Konum') }}</p>
                                @if ($siteSetting?->location_coordinates)
                                    <p class="text-xs text-outline tracking-wider font-mono">{{ $siteSetting->location_coordinates }}</p>
                                @endif
                                <p class="text-xs text-outline">{{ app()->isLocale('en') ? 'Ask our team for the easiest route and transport options.' : 'En kolay rota ve ulaşım seçenekleri için ekibimizden bilgi alabilirsiniz.' }}</p>
                            </div>
                        </div>
                        <div class="pt-6 mt-6 border-t border-surface-container-high/60">
                            <a href="#mapSection" class="inline-flex items-center gap-2 font-label-sm text-label-sm uppercase tracking-wider text-secondary hover:text-primary transition-colors">
                                <span>{{ app()->isLocale('en') ? 'View on Interactive Map' : 'Haritada İnceleyin' }}</span>
                                <span class="material-symbols-outlined text-[18px]">explore</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. CONTACT FORM & RESIDENCE SANCTUARY SECTION -->
        <section class="w-full py-16 lg:py-24 bg-surface" id="contactForm">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
                <div class="max-w-3xl mx-auto space-y-8">
                        <div>
                            <span class="font-label-md text-label-md uppercase text-secondary tracking-[0.26em] block">
                                {{ app()->isLocale('en') ? 'Direct Liaison' : 'Doğrudan İletişim' }}
                            </span>
                            <h2 class="font-headline-lg text-headline-lg lg:text-display-sm text-on-surface mt-1">
                                {{ app()->isLocale('en') ? 'Send Us a Curated Note' : 'Bize Mesajınızı İletin' }}
                            </h2>
                            <p class="font-body-md text-body-md text-on-surface-variant mt-2 font-light">
                                {{ app()->isLocale('en')
                                    ? 'Whether you are planning a business trip, a family stay or a short city break, our team is ready to help.'
                                    : 'İş seyahati, aile konaklaması veya kısa bir şehir ziyareti planlarken ihtiyaç duyduğunuz her konuda ekibimize ulaşabilirsiniz.' }}
                            </p>
                        </div>

                        {{-- Success Notification --}}
                        @if (session('contact_success'))
                            <div class="p-6 bg-secondary/10 border-l-4 border-secondary text-on-surface space-y-2">
                                <div class="flex items-center gap-2 text-secondary font-semibold">
                                    <span class="material-symbols-outlined text-[22px]">check_circle</span>
                                    <span class="font-label-md uppercase tracking-wider">{{ app()->isLocale('en') ? 'Message Delivered' : 'Mesajınız İletildi' }}</span>
                                </div>
                                <p class="font-body-sm text-body-sm">{{ session('contact_success') }}</p>
                            </div>
                        @endif

                        {{-- Error Validation Summary --}}
                        @if ($errors->any())
                            <div class="p-6 bg-error/10 border-l-4 border-error text-error space-y-2">
                                <div class="flex items-center gap-2 font-semibold">
                                    <span class="material-symbols-outlined text-[22px]">error</span>
                                    <span class="font-label-md uppercase tracking-wider">{{ app()->isLocale('en') ? 'Please Review Marked Fields' : 'Lütfen İşaretli Alanları Kontrol Edin' }}</span>
                                </div>
                                <ul class="list-disc list-inside text-xs space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Full Name --}}
                                <div class="space-y-2">
                                    <label class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant" for="name">
                                        {{ app()->isLocale('en') ? 'Full Name *' : 'Adınız Soyadınız *' }}
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                           class="w-full bg-surface-container-low px-4 py-3.5 border border-surface-container text-on-surface placeholder:text-outline/60 font-body-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"
                                           placeholder="{{ app()->isLocale('en') ? 'Lady Helena Vance' : 'Örn: Ahmet Yılmaz' }}">
                                </div>

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant" for="email">
                                        {{ app()->isLocale('en') ? 'Email Address *' : 'E-Posta Adresiniz *' }}
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                           class="w-full bg-surface-container-low px-4 py-3.5 border border-surface-container text-on-surface placeholder:text-outline/60 font-body-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"
                                           placeholder="{{ app()->isLocale('en') ? 'helena@domain.com' : 'ahmet@eposta.com' }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Phone --}}
                                <div class="space-y-2">
                                    <label class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant" for="phone">
                                        {{ app()->isLocale('en') ? 'Phone / Mobile' : 'Telefon Numaranız' }}
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                           class="w-full bg-surface-container-low px-4 py-3.5 border border-surface-container text-on-surface placeholder:text-outline/60 font-body-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"
                                           placeholder="+90 532 000 00 00">
                                </div>

                                {{-- Subject --}}
                                <div class="space-y-2">
                                    <label class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant" for="subject">
                                        {{ app()->isLocale('en') ? 'Inquiry Subject' : 'Konu / Talep Türü' }}
                                    </label>
                                    <select id="subject" name="subject"
                                            class="w-full bg-surface-container-low px-4 py-3.5 border border-surface-container text-on-surface font-body-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all">
                                        <option value="{{ app()->isLocale('en') ? 'General Inquiry' : 'Genel Bilgi & Sorular' }}">{{ app()->isLocale('en') ? 'General Inquiry' : 'Genel Bilgi & Sorular' }}</option>
                                        <option value="{{ app()->isLocale('en') ? 'Room Reservation' : 'Oda & Rezervasyon' }}">{{ app()->isLocale('en') ? 'Room Reservation' : 'Oda & Rezervasyon' }}</option>
                                        <option value="{{ app()->isLocale('en') ? 'Transport Information' : 'Ulaşım Bilgisi' }}">{{ app()->isLocale('en') ? 'Transport Information' : 'Ulaşım Bilgisi' }}</option>
                                        <option value="{{ app()->isLocale('en') ? 'Private Event & Wedding' : 'Özel Etkinlik & Kutlama' }}">{{ app()->isLocale('en') ? 'Private Event & Wedding' : 'Özel Etkinlik & Kutlama' }}</option>
                                        <option value="{{ app()->isLocale('en') ? 'Press & Partnerships' : 'Basın & İş Birlikleri' }}">{{ app()->isLocale('en') ? 'Press & Partnerships' : 'Basın & İş Birlikleri' }}</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Message Textarea --}}
                            <div class="space-y-2">
                                <label class="block font-label-sm text-label-sm uppercase tracking-wider text-on-surface-variant" for="message">
                                    {{ app()->isLocale('en') ? 'Your Curated Message *' : 'Mesajınız *' }}
                                </label>
                                <textarea id="message" name="message" rows="5" required
                                          class="w-full bg-surface-container-low p-4 border border-surface-container text-on-surface placeholder:text-outline/60 font-body-sm focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"
                                          placeholder="{{ app()->isLocale('en') ? 'Tell us your dates, room preference or any questions about your stay...' : 'Tarih aralığınızı, oda tercihinizi veya konaklamayla ilgili sorunuzu yazabilirsiniz...' }}">{{ old('message') }}</textarea>
                            </div>

                            {{-- Privacy Note & Button --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                                <span class="font-body-sm text-xs text-outline max-w-sm">
                                    {{ app()->isLocale('en')
                                        ? 'By submitting, you agree to our confidential handling of your correspondence.'
                                        : 'Göndererek kişisel verilerinizin gizlilik politikamız kapsamında işlenmesini kabul etmiş olursunuz.' }}
                                </span>

                                <button type="submit" class="px-8 py-4 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors duration-300 font-label-md text-label-md uppercase tracking-[0.24em] inline-flex items-center justify-center gap-3 flex-shrink-0 shadow-sm">
                                    <span>{{ app()->isLocale('en') ? 'Send Message' : 'Mesajı Gönder' }}</span>
                                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
        </section>

        <!-- 4. GRAND GOOGLE MAPS & PENINSULA LOCATION SECTION -->
        <section class="w-full py-16 lg:py-20 bg-surface-container-low border-t border-surface-container" id="mapSection">
            <div class="max-w-[1440px] mx-auto px-6 lg:px-12 space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div class="space-y-2 max-w-2xl">
                        <span class="font-label-sm text-label-sm uppercase tracking-[0.26em] text-secondary block">
                            {{ $siteSetting?->translated('location_eyebrow') ?: (app()->isLocale('en') ? 'Getting Here' : 'Ulaşım') }}
                        </span>
                        <h2 class="font-headline-md text-headline-md lg:text-headline-lg text-on-surface font-normal">
                            {{ $siteSetting?->translated('location_title') ?: (app()->isLocale('en') ? 'Our Location' : 'Konumumuz') }}
                        </h2>
                        <p class="font-body-sm text-body-sm text-on-surface-variant font-light">
                            {{ $siteSetting?->translated('location_description') ?: (app()->isLocale('en')
                                ? 'You can easily reach our hotel by getting directions.'
                                : 'Yol tarifi alarak otelimize kolayca ulaşabilirsiniz.') }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ $siteSetting?->directionsUrl() }}"
                           target="_blank" rel="noopener noreferrer"
                           class="px-6 py-3.5 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-sm text-label-sm uppercase tracking-wider inline-flex items-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">directions</span>
                            <span>{{ app()->isLocale('en') ? 'Get Directions on Google Maps' : 'Google Haritalar\'da Yol Tarifi Al' }}</span>
                        </a>
                    </div>
                </div>

                {{-- Interactive Map Frame --}}
                <div class="relative w-full h-[440px] lg:h-[500px] bg-surface-container border border-surface-container-high overflow-hidden shadow-lg">
                    <iframe src="{{ $siteSetting?->mapEmbedUrl() }}"
                            class="w-full h-full border-0 filter grayscale contrast-125 opacity-95 hover:grayscale-0 hover:opacity-100 transition-all duration-700"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                    {{-- Floating Info Badge on the Map --}}
                    <div class="absolute bottom-6 left-6 max-w-sm bg-surface/95 backdrop-blur-md p-5 border border-surface-container-high shadow-xl hidden sm:block">
                        <div class="flex items-center gap-2 text-secondary mb-2">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                            <span class="font-label-sm text-[11px] uppercase tracking-wider font-semibold">{{ $siteSetting?->location_pin_label ?: ($siteSetting?->hotel_name ?? 'EtnoCity Otel') }}</span>
                        </div>
                        <p class="font-body-sm text-xs text-on-surface font-medium leading-relaxed">{{ $siteSetting?->address ?? (app()->isLocale('en') ? 'Central Location' : 'Merkezi Konum') }}</p>
                        @if ($siteSetting?->location_coordinates)
                            <p class="font-mono text-[11px] text-outline mt-1.5">{{ $siteSetting->location_coordinates }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.app>
