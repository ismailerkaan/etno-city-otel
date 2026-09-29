<div class="w-full bg-surface/95 backdrop-blur-xl shadow-2xl p-6 lg:p-8 relative" id="booking-widget">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">

        {{-- Check In --}}
        <div class="space-y-1.5">
            <span class="font-label-sm text-label-sm uppercase text-secondary tracking-[0.2em] block">Giriş</span>
            <div class="relative bg-surface-container px-4 py-3 flex items-center justify-between cursor-pointer group" id="checkin-trigger">
                <div class="flex flex-col">
                    <span class="font-title-md text-title-md text-on-surface font-normal" id="checkin-display">Tarih Seçin</span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase" id="checkin-day">Gün · 15:00</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px] group-hover:translate-y-0.5 transition-transform">calendar_today</span>
            </div>
        </div>

        {{-- Check Out --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <span class="font-label-sm text-label-sm uppercase text-secondary tracking-[0.2em] block">Çıkış</span>
                <span class="font-label-sm text-[10px] bg-secondary-fixed text-on-secondary-fixed px-1.5 py-0.5" id="nights-badge">Gece Seçin</span>
            </div>
            <div class="relative bg-surface-container px-4 py-3 flex items-center justify-between cursor-pointer group" id="checkout-trigger">
                <div class="flex flex-col">
                    <span class="font-title-md text-title-md text-on-surface font-normal" id="checkout-display">Tarih Seçin</span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase" id="checkout-day">Gün · 12:00</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px] group-hover:translate-y-0.5 transition-transform">event</span>
            </div>
        </div>

        {{-- Guests --}}
        <div class="space-y-1.5">
            <span class="font-label-sm text-label-sm uppercase text-secondary tracking-[0.2em] block">Kişi Sayısı</span>
            <div class="relative bg-surface-container px-4 py-3 flex items-center justify-between cursor-pointer group" id="guests-trigger">
                <div class="flex flex-col">
                    <span class="font-title-md text-title-md text-on-surface font-normal" id="guests-display">2 Yetişkin</span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase" id="children-display">0 Çocuk</span>
                </div>
                <span class="material-symbols-outlined text-secondary text-[20px]">group</span>
            </div>
        </div>

        {{-- Submit CTA --}}
        <div class="flex flex-col justify-end">
            <a href="{{ route('reservation.step1') }}" class="w-full h-[58px] bg-primary text-on-primary hover:bg-secondary hover:text-on-secondary transition-all duration-300 font-label-md text-label-md uppercase tracking-[0.22em] flex items-center justify-center gap-3 px-4 group shadow-md">
                <span>Müsaitlik Sorgula</span>
                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
    </div>

    {{-- Trust Badges Bar --}}
    <div class="mt-4 pt-3 flex flex-wrap items-center justify-between gap-4 font-label-sm text-label-sm text-on-surface-variant tracking-wider">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
            <span>En İyi Doğrudan Fiyat Garantisi</span>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[16px]">wine_bar</span>
            <span>Dom Pérignon Karşılama İkramı &amp; Soğuk Sıkım Botanikler</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[16px]">concierge</span>
            <span>7/24 Özel Concierge Hizmeti</span>
        </div>
    </div>
</div>

{{-- Floating Calendars (Fixed position, outside any overflow container) --}}
<div id="checkin-calendar" style="display:none;position:fixed;z-index:9999;background:#fcf9f4;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);padding:16px;width:288px;border:1px solid #ebe8e3;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <button id="checkin-prev" type="button" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;border:none;color:#1c1c19;">
            <span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span>
        </button>
        <span id="checkin-month-label" style="font-family:Manrope,sans-serif;font-size:11px;font-weight:600;letter-spacing:0.2em;text-transform:uppercase;color:#1c1c19;"></span>
        <button id="checkin-next" type="button" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;border:none;color:#1c1c19;">
            <span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span>
        </button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:8px;">
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pt</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Sa</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Ça</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pe</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Cu</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Ct</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pz</span>
    </div>
    <div id="checkin-days" style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;"></div>
</div>

<div id="checkout-calendar" style="display:none;position:fixed;z-index:9999;background:#fcf9f4;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);padding:16px;width:288px;border:1px solid #ebe8e3;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <button id="checkout-prev" type="button" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;border:none;color:#1c1c19;">
            <span class="material-symbols-outlined" style="font-size:18px;">chevron_left</span>
        </button>
        <span id="checkout-month-label" style="font-family:Manrope,sans-serif;font-size:11px;font-weight:600;letter-spacing:0.2em;text-transform:uppercase;color:#1c1c19;"></span>
        <button id="checkout-next" type="button" style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;border:none;color:#1c1c19;">
            <span class="material-symbols-outlined" style="font-size:18px;">chevron_right</span>
        </button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(7,1fr);margin-bottom:8px;">
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pt</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Sa</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Ça</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pe</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Cu</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Ct</span>
        <span style="text-align:center;font-size:10px;color:#735a3c;text-transform:uppercase;padding:4px 0;font-family:Manrope,sans-serif;font-weight:600;">Pz</span>
    </div>
    <div id="checkout-days" style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;"></div>
</div>

{{-- Guests Dropdown --}}
<div id="guests-dropdown" style="display:none;position:fixed;z-index:9999;background:#fcf9f4;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);padding:20px;width:256px;border:1px solid #ebe8e3;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <p style="font-family:Manrope,sans-serif;font-size:1.125rem;font-weight:500;color:#1c1c19;margin:0;">Yetişkin</p>
            <p style="font-family:Manrope,sans-serif;font-size:10px;color:#444748;text-transform:uppercase;margin:0;">13 yaş ve üzeri</p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="adults-minus" type="button" style="width:32px;height:32px;border:1px solid #ebe8e3;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;color:#1c1c19;">
                <span class="material-symbols-outlined" style="font-size:16px;">remove</span>
            </button>
            <span id="adults-count" style="font-family:Manrope,sans-serif;font-size:1.125rem;font-weight:500;color:#1c1c19;min-width:16px;text-align:center;">2</span>
            <button id="adults-plus" type="button" style="width:32px;height:32px;border:1px solid #ebe8e3;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;color:#1c1c19;">
                <span class="material-symbols-outlined" style="font-size:16px;">add</span>
            </button>
        </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <p style="font-family:Manrope,sans-serif;font-size:1.125rem;font-weight:500;color:#1c1c19;margin:0;">Çocuk</p>
            <p style="font-family:Manrope,sans-serif;font-size:10px;color:#444748;text-transform:uppercase;margin:0;">0–12 yaş</p>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="children-minus" type="button" style="width:32px;height:32px;border:1px solid #ebe8e3;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;color:#1c1c19;">
                <span class="material-symbols-outlined" style="font-size:16px;">remove</span>
            </button>
            <span id="children-count" style="font-family:Manrope,sans-serif;font-size:1.125rem;font-weight:500;color:#1c1c19;min-width:16px;text-align:center;">0</span>
            <button id="children-plus" type="button" style="width:32px;height:32px;border:1px solid #ebe8e3;display:flex;align-items:center;justify-content:center;cursor:pointer;background:none;color:#1c1c19;">
                <span class="material-symbols-outlined" style="font-size:16px;">add</span>
            </button>
        </div>
    </div>
    <button id="guests-done" type="button" style="width:100%;padding:8px 0;background:#000;color:#fff;font-family:Manrope,sans-serif;font-size:11px;font-weight:600;letter-spacing:0.2em;text-transform:uppercase;border:none;cursor:pointer;">Tamam</button>
</div>

<script>
(function () {
    var MONTHS = ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'];
    var DAYS = ['Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi','Pazar'];

    var checkinDate  = null;
    var checkoutDate = null;
    var adults   = 2;
    var children = 0;

    var calState = {
        checkin:  { year: new Date().getFullYear(), month: new Date().getMonth() },
        checkout: { year: new Date().getFullYear(), month: new Date().getMonth() }
    };

    function formatDisplay(d) {
        return d.getDate() + ' ' + MONTHS[d.getMonth()].substring(0,3) + ', ' + d.getFullYear();
    }

    function formatDayName(d) {
        return DAYS[(d.getDay() + 6) % 7];
    }

    function nightsBetween(a, b) {
        return Math.round((b - a) / 86400000);
    }

    function renderCalendar(type) {
        var s = calState[type];
        var daysEl  = document.getElementById(type + '-days');
        var labelEl = document.getElementById(type + '-month-label');
        if (!daysEl || !labelEl) return;

        labelEl.textContent = MONTHS[s.month] + ' ' + s.year;
        daysEl.innerHTML = '';

        var first = new Date(s.year, s.month, 1);
        var total = new Date(s.year, s.month + 1, 0).getDate();
        var offset = (first.getDay() + 6) % 7;
        var today = new Date(); today.setHours(0,0,0,0);

        for (var i = 0; i < offset; i++) {
            daysEl.insertAdjacentHTML('beforeend', '<div></div>');
        }

        for (var d = 1; d <= total; d++) {
            var date = new Date(s.year, s.month, d);
            date.setHours(0,0,0,0);

            var isPast = date < today;
            var isBeforeCheckin = (type === 'checkout') && checkinDate && date <= checkinDate;
            var disabled = isPast || isBeforeCheckin;

            var isCheckin  = checkinDate  && date.getTime() === checkinDate.getTime();
            var isCheckout = checkoutDate && date.getTime() === checkoutDate.getTime();
            var isInRange  = checkinDate && checkoutDate && date > checkinDate && date < checkoutDate;

            var bg = 'transparent', color = '#1c1c19', cursor = 'pointer', opacity = '1';
            if (disabled) { color = '#444748'; opacity = '0.3'; cursor = 'not-allowed'; }
            else if (isCheckin || isCheckout) { bg = '#000'; color = '#fff'; }
            else if (isInRange) { bg = '#ffddb8'; color = '#291802'; }

            var style = 'text-align:center;padding:6px 2px;font-size:13px;font-family:Manrope,sans-serif;background:' + bg + ';color:' + color + ';cursor:' + cursor + ';opacity:' + opacity + ';border-radius:0;';
            var disAttr = disabled ? 'data-disabled="true"' : '';
            daysEl.insertAdjacentHTML('beforeend', '<div style="' + style + '" data-date="' + date.toISOString() + '" ' + disAttr + '>' + d + '</div>');
        }

        daysEl.querySelectorAll('[data-date]:not([data-disabled])').forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                if (!el.dataset.disabled) el.style.background = '#735a3c', el.style.color = '#fff';
            });
            el.addEventListener('mouseleave', function() {
                var d2 = new Date(el.dataset.date);
                var isCI = checkinDate  && d2.getTime() === checkinDate.getTime();
                var isCO = checkoutDate && d2.getTime() === checkoutDate.getTime();
                var isIR = checkinDate && checkoutDate && d2 > checkinDate && d2 < checkoutDate;
                if (isCI || isCO) { el.style.background = '#000'; el.style.color = '#fff'; }
                else if (isIR) { el.style.background = '#ffddb8'; el.style.color = '#291802'; }
                else { el.style.background = 'transparent'; el.style.color = '#1c1c19'; }
            });
            el.addEventListener('click', function() {
                var selected = new Date(el.dataset.date);
                if (type === 'checkin') {
                    checkinDate = selected;
                    if (checkoutDate && checkoutDate <= checkinDate) checkoutDate = null;
                    updateDisplays();
                    closeAll();
                    setTimeout(function() { openDropdown('checkout'); }, 150);
                } else {
                    checkoutDate = selected;
                    updateDisplays();
                    closeAll();
                }
            });
        });
    }

    function updateDisplays() {
        var cd = document.getElementById('checkin-display');
        var cday = document.getElementById('checkin-day');
        if (checkinDate) {
            cd.textContent = formatDisplay(checkinDate);
            cday.textContent = formatDayName(checkinDate) + ' · 15:00';
        } else {
            cd.textContent = 'Tarih Seçin';
            cday.textContent = 'Gün · 15:00';
        }

        var od = document.getElementById('checkout-display');
        var oday = document.getElementById('checkout-day');
        var nb = document.getElementById('nights-badge');
        if (checkoutDate) {
            od.textContent = formatDisplay(checkoutDate);
            oday.textContent = formatDayName(checkoutDate) + ' · 12:00';
        } else {
            od.textContent = 'Tarih Seçin';
            oday.textContent = 'Gün · 12:00';
        }
        if (checkinDate && checkoutDate) {
            nb.textContent = nightsBetween(checkinDate, checkoutDate) + ' Gece';
        } else {
            nb.textContent = 'Gece Seçin';
        }

        document.getElementById('guests-display').textContent = adults + ' Yetişkin';
        document.getElementById('children-display').textContent = children + ' Çocuk';
        document.getElementById('adults-count').textContent = adults;
        document.getElementById('children-count').textContent = children;

        renderCalendar('checkin');
        renderCalendar('checkout');
    }

    function openDropdown(type) {
        closeAll();
        var el = document.getElementById(type === 'guests' ? 'guests-dropdown' : type + '-calendar');
        var trigger = document.getElementById(type + '-trigger');
        if (!el || !trigger) return;
        var rect = trigger.getBoundingClientRect();
        el.style.top  = (rect.bottom + window.scrollY + 6) + 'px';
        el.style.left = rect.left + 'px';
        el.style.display = 'block';
    }

    function closeAll() {
        ['checkin-calendar','checkout-calendar','guests-dropdown'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
    }

    // Tetikleyiciler
    document.getElementById('checkin-trigger').addEventListener('click', function(e) {
        e.stopPropagation();
        var cal = document.getElementById('checkin-calendar');
        if (cal.style.display === 'none' || !cal.style.display) { openDropdown('checkin'); }
        else { closeAll(); }
    });

    document.getElementById('checkout-trigger').addEventListener('click', function(e) {
        e.stopPropagation();
        var cal = document.getElementById('checkout-calendar');
        if (cal.style.display === 'none' || !cal.style.display) { openDropdown('checkout'); }
        else { closeAll(); }
    });

    document.getElementById('guests-trigger').addEventListener('click', function(e) {
        e.stopPropagation();
        var dd = document.getElementById('guests-dropdown');
        if (dd.style.display === 'none' || !dd.style.display) { openDropdown('guests'); }
        else { closeAll(); }
    });

    document.addEventListener('click', function(e) {
        var w = document.getElementById('booking-widget');
        var cc = document.getElementById('checkin-calendar');
        var oc = document.getElementById('checkout-calendar');
        var gd = document.getElementById('guests-dropdown');
        if (w && !w.contains(e.target) && !cc.contains(e.target) && !oc.contains(e.target) && !gd.contains(e.target)) {
            closeAll();
        }
    });

    // Ay navigasyonu
    document.getElementById('checkin-prev').addEventListener('click', function(e) {
        e.stopPropagation();
        calState.checkin.month--;
        if (calState.checkin.month < 0) { calState.checkin.month = 11; calState.checkin.year--; }
        renderCalendar('checkin');
    });
    document.getElementById('checkin-next').addEventListener('click', function(e) {
        e.stopPropagation();
        calState.checkin.month++;
        if (calState.checkin.month > 11) { calState.checkin.month = 0; calState.checkin.year++; }
        renderCalendar('checkin');
    });
    document.getElementById('checkout-prev').addEventListener('click', function(e) {
        e.stopPropagation();
        calState.checkout.month--;
        if (calState.checkout.month < 0) { calState.checkout.month = 11; calState.checkout.year--; }
        renderCalendar('checkout');
    });
    document.getElementById('checkout-next').addEventListener('click', function(e) {
        e.stopPropagation();
        calState.checkout.month++;
        if (calState.checkout.month > 11) { calState.checkout.month = 0; calState.checkout.year++; }
        renderCalendar('checkout');
    });

    // Misafir sayaçları
    document.getElementById('adults-plus').addEventListener('click', function(e) {
        e.stopPropagation(); if (adults < 10) { adults++; updateDisplays(); }
    });
    document.getElementById('adults-minus').addEventListener('click', function(e) {
        e.stopPropagation(); if (adults > 1) { adults--; updateDisplays(); }
    });
    document.getElementById('children-plus').addEventListener('click', function(e) {
        e.stopPropagation(); if (children < 6) { children++; updateDisplays(); }
    });
    document.getElementById('children-minus').addEventListener('click', function(e) {
        e.stopPropagation(); if (children > 0) { children--; updateDisplays(); }
    });
    document.getElementById('guests-done').addEventListener('click', function(e) {
        e.stopPropagation(); closeAll();
    });

    // İlk render
    renderCalendar('checkin');
    renderCalendar('checkout');
})();
</script>
