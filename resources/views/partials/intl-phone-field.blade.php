@php
    use App\Helpers\PhoneHelper;
    $fieldId = $id ?? 'intl_' . uniqid();
    $fieldName = $name ?? 'phone_intl';
    $fieldLabel = $label ?? trans('file.Whatsapp number');
    $defaultDial = $defaultDial ?? '237';
    [$selectedDial, $localValue] = PhoneHelper::splitIntl($value ?? old($fieldName, ''), $defaultDial);
    $countries = PhoneHelper::countries();
    $selectedCountry = null;
    foreach ($countries as $c) {
        if ($c['dial'] === $selectedDial) { $selectedCountry = $c; break; }
    }
    if (!$selectedCountry) {
        $selectedCountry = ['iso' => 'CM', 'name' => 'Cameroon', 'dial' => $selectedDial, 'flag' => '🌍'];
    }
    $required = $required ?? true;
    $hiddenValue = $localValue !== '' ? ('+' . $selectedDial . $localValue) : '';
    $hint = $hint ?? trans('file.Choose your country then enter your number');
@endphp
<div class="intl-phone-field" data-intl-phone>
    @if(!empty($fieldLabel))
        <label for="{{ $fieldId }}_local" class="intl-phone-field__label">{!! $fieldLabel !!}</label>
    @endif
    <div class="intl-phone-field__wrap">
        <div class="intl-phone-field__country">
            <button type="button" class="intl-phone-field__trigger" data-intl-trigger aria-haspopup="listbox" aria-expanded="false">
                <span class="intl-phone-field__flag" data-intl-flag>{{ $selectedCountry['flag'] }}</span>
                <span class="intl-phone-field__dial" data-intl-dial-label>+{{ $selectedDial }}</span>
                <i class="fa fa-chevron-down intl-phone-field__chev" aria-hidden="true"></i>
            </button>
            <input type="hidden" data-intl-dial value="{{ $selectedDial }}">
            <div class="intl-phone-field__dropdown" data-intl-dropdown hidden>
                <div class="intl-phone-field__search">
                    <i class="fa fa-search"></i>
                    <input type="text" class="intl-phone-field__search-input" data-intl-search
                           placeholder="{{ trans('file.Search country') }}" autocomplete="off">
                </div>
                <ul class="intl-phone-field__list" data-intl-list role="listbox">
                    @foreach($countries as $c)
                        <li class="intl-phone-field__option {{ $c['dial'] === $selectedDial ? 'is-selected' : '' }}"
                            role="option"
                            data-intl-option
                            data-dial="{{ $c['dial'] }}"
                            data-iso="{{ $c['iso'] }}"
                            data-search="{{ strtolower($c['name']) }} {{ $c['dial'] }} {{ strtolower($c['iso']) }}">
                            <span class="intl-phone-field__opt-flag">{{ $c['flag'] }}</span>
                            <span class="intl-phone-field__opt-name">{{ $c['name'] }}</span>
                            <span class="intl-phone-field__opt-dial">+{{ $c['dial'] }}</span>
                        </li>
                    @endforeach
                    <li class="intl-phone-field__empty" data-intl-empty hidden>{{ trans('file.No country found') }}</li>
                </ul>
            </div>
        </div>
        <input type="tel"
               id="{{ $fieldId }}_local"
               class="intl-phone-field__input"
               value="{{ $localValue }}"
               inputmode="numeric"
               autocomplete="tel"
               placeholder="{{ trans('file.Phone number') }}"
               data-intl-local>
        <input type="hidden"
               id="{{ $fieldId }}"
               name="{{ $fieldName }}"
               value="{{ $hiddenValue }}"
               {{ $required ? 'required' : '' }}
               data-intl-hidden>
    </div>
    @if(!empty($hint))
        <small class="intl-phone-field__hint">{{ $hint }}</small>
    @endif
</div>

@if(empty($GLOBALS['__intlPhoneAssets']))
@php($GLOBALS['__intlPhoneAssets'] = true)
<style>
    .intl-phone-field { margin-bottom: 12px; }
    .intl-phone-field__label { display:block; font-weight:700; color:#0a2350; margin-bottom:6px; font-size:13px; }
    .intl-phone-field__wrap {
        display:flex; align-items:stretch;
        border:1px solid #b8c9e8; border-radius:14px;
        background:#fff; box-shadow:0 1px 0 rgba(10,35,80,.04);
        transition:border-color .2s, box-shadow .2s;
    }
    .intl-phone-field__wrap:focus-within { border-color:#1d4ed8; box-shadow:0 0 0 3px rgba(29,78,216,.12); }
    .intl-phone-field__country { position:relative; flex:0 0 auto; }
    .intl-phone-field__trigger {
        display:flex; align-items:center; gap:6px; height:100%;
        padding:0 12px; border:0; cursor:pointer;
        background:linear-gradient(180deg,#eef4ff,#e3ecfb);
        border-right:1px solid #c5d3ea;
        border-radius:14px 0 0 14px;
    }
    .intl-phone-field__flag { font-size:16px; line-height:1; }
    .intl-phone-field__dial { font-weight:700; font-size:14px; color:#0a2350; }
    .intl-phone-field__chev { font-size:10px; color:#64748b; transition:transform .2s; }
    .intl-phone-field__country.is-open .intl-phone-field__chev { transform:rotate(180deg); }
    .intl-phone-field__input {
        flex:1; border:0; background:transparent; padding:13px 14px;
        font-size:16px; font-weight:500; color:#14223f !important;
        -webkit-text-fill-color:#14223f;
        letter-spacing:.3px; outline:none; min-width:0;
    }
    .intl-phone-field__input::placeholder { color:#94a3b8 !important; -webkit-text-fill-color:#94a3b8; font-weight:400; }
    .intl-phone-field__hint { display:block; margin-top:5px; color:#6b7a93; font-size:11.5px; }

    .intl-phone-field__dropdown {
        position:absolute; top:calc(100% + 6px); left:0; z-index:60;
        width:290px; max-width:78vw; background:#fff;
        border:1px solid #d6e0f2; border-radius:14px;
        box-shadow:0 18px 44px rgba(3,12,28,.28);
        overflow:hidden;
    }
    .intl-phone-field__search {
        display:flex; align-items:center; gap:8px;
        padding:10px 12px; border-bottom:1px solid #eef2f9; background:#f8fafd;
    }
    .intl-phone-field__search i { color:#94a3b8; font-size:13px; }
    .intl-phone-field__search-input {
        flex:1; border:0; outline:none; background:transparent;
        font-size:14px; color:#14223f !important; -webkit-text-fill-color:#14223f;
    }
    .intl-phone-field__search-input::placeholder { color:#94a3b8; }
    .intl-phone-field__list { list-style:none; margin:0; padding:6px; max-height:240px; overflow-y:auto; }
    .intl-phone-field__option {
        display:flex; align-items:center; gap:10px;
        padding:9px 10px; border-radius:9px; cursor:pointer; font-size:14px;
    }
    .intl-phone-field__option:hover { background:#eef4ff; }
    .intl-phone-field__option.is-selected { background:#fff2e6; }
    .intl-phone-field__opt-flag { font-size:16px; line-height:1; }
    .intl-phone-field__opt-name { flex:1; color:#14223f; }
    .intl-phone-field__opt-dial { color:#64748b; font-weight:600; font-size:13px; }
    .intl-phone-field__empty { padding:14px 10px; text-align:center; color:#94a3b8; font-size:13px; }
</style>
<script>
(function () {
    if (window.__intlPhoneInit) { return; }
    window.__intlPhoneInit = true;

    function digitsOnly(v) { return String(v || '').replace(/\D/g, ''); }

    // Pull the local (national) part out of the visible value, dropping the
    // dial-code prefix if it is present, so callers only type the number.
    function extractLocal(displayVal, dial) {
        var d = digitsOnly(displayVal);
        if (dial && d.indexOf(dial) === 0) { d = d.slice(dial.length); }
        return d.replace(/^0+/, '');
    }

    // Always show "+<dial> <local>" so the prefix is added automatically.
    function formatDisplay(dial, local) {
        return local ? ('+' + dial + ' ' + local) : ('+' + dial + ' ');
    }

    function sync(wrap) {
        if (!wrap) { return; }
        var dialInput = wrap.querySelector('[data-intl-dial]');
        var label = wrap.querySelector('[data-intl-dial-label]');
        var local = wrap.querySelector('[data-intl-local]');
        var hidden = wrap.querySelector('[data-intl-hidden]');
        if (!dialInput || !local || !hidden) { return; }
        var dial = digitsOnly(dialInput.value);
        var localDigits = extractLocal(local.value, dial);
        if (label) { label.textContent = '+' + dial; }
        hidden.value = localDigits ? ('+' + dial + localDigits) : '';
        var formatted = formatDisplay(dial, localDigits);
        if (local.value !== formatted) { local.value = formatted; }
    }

    function closeAll(except) {
        document.querySelectorAll('[data-intl-phone]').forEach(function (wrap) {
            var country = wrap.querySelector('.intl-phone-field__country');
            var dd = wrap.querySelector('[data-intl-dropdown]');
            var trigger = wrap.querySelector('[data-intl-trigger]');
            if (!dd || wrap === except) { return; }
            dd.hidden = true;
            if (country) { country.classList.remove('is-open'); }
            if (trigger) { trigger.setAttribute('aria-expanded', 'false'); }
        });
    }

    document.addEventListener('click', function (e) {
        var trigger = e.target.closest('[data-intl-trigger]');
        if (trigger) {
            e.preventDefault();
            var wrap = trigger.closest('[data-intl-phone]');
            var country = wrap.querySelector('.intl-phone-field__country');
            var dd = wrap.querySelector('[data-intl-dropdown]');
            var isOpen = !dd.hidden;
            closeAll(isOpen ? null : wrap);
            dd.hidden = isOpen;
            country.classList.toggle('is-open', !isOpen);
            trigger.setAttribute('aria-expanded', String(!isOpen));
            if (!isOpen) {
                var search = wrap.querySelector('[data-intl-search]');
                if (search) { search.value = ''; filterList(wrap, ''); setTimeout(function () { search.focus(); }, 30); }
            }
            return;
        }

        var option = e.target.closest('[data-intl-option]');
        if (option) {
            var wrap2 = option.closest('[data-intl-phone]');
            var dialInput = wrap2.querySelector('[data-intl-dial]');
            var flag = wrap2.querySelector('[data-intl-flag]');
            var localEl = wrap2.querySelector('[data-intl-local]');
            // Keep the digits already typed while swapping the prefix.
            var localDigits = extractLocal(localEl ? localEl.value : '', digitsOnly(dialInput.value));
            var newDial = option.getAttribute('data-dial');
            dialInput.value = newDial;
            if (localEl) { localEl.value = formatDisplay(newDial, localDigits); }
            if (flag) { flag.textContent = option.querySelector('.intl-phone-field__opt-flag').textContent; }
            wrap2.querySelectorAll('[data-intl-option]').forEach(function (o) { o.classList.remove('is-selected'); });
            option.classList.add('is-selected');
            sync(wrap2);
            closeAll(null);
            if (localEl) { localEl.focus(); }
            return;
        }

        if (!e.target.closest('[data-intl-phone]')) {
            closeAll(null);
        }
    });

    function filterList(wrap, term) {
        term = String(term || '').trim().toLowerCase();
        var visible = 0;
        wrap.querySelectorAll('[data-intl-option]').forEach(function (opt) {
            var match = !term || opt.getAttribute('data-search').indexOf(term) !== -1;
            opt.hidden = !match;
            if (match) { visible++; }
        });
        var empty = wrap.querySelector('[data-intl-empty]');
        if (empty) { empty.hidden = visible !== 0; }
    }

    document.addEventListener('input', function (e) {
        if (e.target.matches('[data-intl-search]')) {
            filterList(e.target.closest('[data-intl-phone]'), e.target.value);
            return;
        }
        if (e.target.matches('[data-intl-local]')) {
            sync(e.target.closest('[data-intl-phone]'));
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeAll(null); }
    });

    document.querySelectorAll('[data-intl-phone]').forEach(sync);
})();
</script>
@endif
