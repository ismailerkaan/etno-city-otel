@props(['siteSetting' => null])

{{-- Çerez onay bandı: analitik çerezler yalnızca "Kabul Et" sonrası yüklenir --}}
<div id="cookie-consent" class="hidden fixed inset-x-0 bottom-0 z-[60] p-4 sm:p-6" role="dialog" aria-live="polite" aria-labelledby="cookie-consent-title">
    <div class="max-w-4xl mx-auto bg-surface border border-surface-container-high shadow-[0_8px_32px_rgba(0,0,0,0.12)] p-5 sm:p-6 flex flex-col md:flex-row md:items-center gap-5">
        <div class="flex-1 space-y-1.5">
            <p id="cookie-consent-title" class="font-label-md text-label-md uppercase tracking-[0.2em] text-on-surface">{{ __('cookies.banner_title') }}</p>
            <p class="font-body-sm text-sm text-on-surface-variant font-light leading-relaxed">
                {{ __('cookies.banner_text') }}
                <a href="{{ route('cookie-policy') }}" class="text-secondary underline underline-offset-4">{{ __('cookies.policy_link') }}</a>.
            </p>
        </div>
        <div class="flex gap-3 flex-shrink-0">
            <button type="button" data-cookie-choice="rejected" class="flex-1 md:flex-none px-6 py-3 border border-outline-variant text-on-surface hover:border-on-surface transition-colors font-label-md text-label-md uppercase tracking-[0.2em]">
                {{ __('cookies.reject') }}
            </button>
            <button type="button" data-cookie-choice="accepted" class="flex-1 md:flex-none px-6 py-3 bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-colors font-label-md text-label-md uppercase tracking-[0.2em]">
                {{ __('cookies.accept') }}
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        const banner = document.getElementById('cookie-consent');
        const measurementId = @json($siteSetting?->google_analytics_measurement_id);

        function readConsent() {
            const match = document.cookie.match(/(?:^|;\s*)cookie_consent=(accepted|rejected)/);
            return match ? match[1] : null;
        }

        function loadAnalytics() {
            if (! measurementId || window.gtagLoaded) {
                return;
            }

            window.gtagLoaded = true;
            window.dataLayer = window.dataLayer || [];
            window.gtag = function () { dataLayer.push(arguments); };
            gtag('js', new Date());
            gtag('config', measurementId);

            const script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(measurementId);
            document.head.appendChild(script);
        }

        function removeAnalyticsCookies() {
            document.cookie.split(';').forEach(function (cookie) {
                const name = cookie.split('=')[0].trim();
                if (name === '_ga' || name.indexOf('_ga_') === 0) {
                    const domain = location.hostname.split('.').slice(-2).join('.');
                    document.cookie = name + '=; Max-Age=0; path=/';
                    document.cookie = name + '=; Max-Age=0; path=/; domain=.' + domain;
                }
            });
        }

        function saveConsent(choice) {
            const secure = location.protocol === 'https:' ? '; Secure' : '';
            document.cookie = 'cookie_consent=' + choice + '; Max-Age=31536000; path=/; SameSite=Lax' + secure;
            banner.classList.add('hidden');

            if (choice === 'accepted') {
                loadAnalytics();
                return;
            }

            removeAnalyticsCookies();
            // Analitik bu sayfada zaten yüklendiyse durdurmak için sayfayı yenile
            if (window.gtagLoaded) {
                location.reload();
            }
        }

        document.querySelectorAll('[data-cookie-choice]').forEach(function (button) {
            button.addEventListener('click', function () {
                saveConsent(button.dataset.cookieChoice);
            });
        });

        document.querySelectorAll('[data-cookie-preferences]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                banner.classList.remove('hidden');
            });
        });

        const consent = readConsent();
        if (consent === 'accepted') {
            loadAnalytics();
        } else if (consent === null) {
            banner.classList.remove('hidden');
        }
    })();
</script>
