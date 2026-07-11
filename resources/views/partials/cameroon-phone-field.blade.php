@php
    $fieldId = $id ?? 'phone_' . uniqid();
    $fieldName = $name ?? 'phone_local';
    $fieldLabel = $label ?? trans('file.Momo Number');
    $localValue = \App\Helpers\PhoneHelper::defaultPaymentLocal($value ?? old($fieldName, ''));
    $simulate = \App\Helpers\PhoneHelper::paymentSimulate();
    $readonly = $readonly ?? $simulate;
    $displayValue = \App\Helpers\PhoneHelper::formatCameroonDisplay($localValue);
    $hint = $hint ?? ($simulate ? trans('file.Payment simulation uses developer number') : trans('file.Enter your number without the country code'));
@endphp
<div class="cm-phone-field" data-cm-phone>
    @if(!empty($fieldLabel))
        <label for="{{ $fieldId }}_display" class="cm-phone-field__label">{!! $fieldLabel !!}</label>
    @endif
    <div class="cm-phone-field__wrap {{ $readonly ? 'is-readonly' : '' }}">
        <div class="cm-phone-field__country" aria-label="Cameroon +237">
            <span class="cm-phone-field__flag" aria-hidden="true">
                <svg width="22" height="16" viewBox="0 0 22 16" xmlns="http://www.w3.org/2000/svg">
                    <rect width="7.33" height="16" fill="#009739"/>
                    <rect x="7.33" width="7.34" height="16" fill="#CE1126"/>
                    <rect x="14.67" width="7.33" height="16" fill="#FCD116"/>
                    <circle cx="14.67" cy="8" r="2.2" fill="#FCD116"/>
                </svg>
            </span>
            <span class="cm-phone-field__dial">+237</span>
            <i class="fa fa-chevron-down cm-phone-field__chev" aria-hidden="true"></i>
        </div>
        <input type="tel"
               id="{{ $fieldId }}_display"
               class="cm-phone-field__input"
               value="{{ $displayValue }}"
               inputmode="numeric"
               autocomplete="tel"
               placeholder="+237 6 XX XX XX XX"
               {{ $readonly ? 'readonly tabindex="-1"' : '' }}
               data-cm-phone-display>
        <input type="hidden"
               id="{{ $fieldId }}"
               name="{{ $fieldName }}"
               value="{{ $localValue }}"
               {{ empty($readonly) ? 'required' : '' }}
               data-cm-phone-hidden>
    </div>
    @if(!empty($hint))
        <small class="cm-phone-field__hint">{{ $hint }}</small>
    @endif
</div>

@if(empty($GLOBALS['__cmPhoneAssets']))
@php($GLOBALS['__cmPhoneAssets'] = true)
<style>
    .cm-phone-field { margin-bottom: 12px; }
    .cm-phone-field__label { display:block; font-weight:700; color:#0a2350; margin-bottom:6px; font-size:13px; }
    .cm-phone-field__wrap {
        display:flex; align-items:stretch;
        border:1px solid #b8c9e8;
        border-radius:14px;
        overflow:hidden;
        background:#fff;
        box-shadow:0 1px 0 rgba(10,35,80,.04);
        transition:border-color .2s, box-shadow .2s;
    }
    .cm-phone-field__wrap:focus-within {
        border-color:#1d4ed8;
        box-shadow:0 0 0 3px rgba(29,78,216,.12);
    }
    .cm-phone-field__wrap.is-readonly { background:#f4f7fc; }
    .cm-phone-field__country {
        display:flex; align-items:center; gap:6px;
        padding:0 12px;
        background:linear-gradient(180deg,#eef4ff,#e3ecfb);
        border-right:1px solid #c5d3ea;
        flex:0 0 auto;
        user-select:none;
    }
    .cm-phone-field__flag { display:flex; line-height:0; border-radius:2px; overflow:hidden; box-shadow:0 0 0 1px rgba(0,0,0,.08); }
    .cm-phone-field__dial { font-weight:700; font-size:14px; color:#0a2350; }
    .cm-phone-field__chev { font-size:10px; color:#64748b; margin-left:2px; }
    .cm-phone-field__input {
        flex:1; border:0; background:transparent;
        padding:13px 14px;
        font-size:16px; font-weight:500; color:#334155;
        letter-spacing:.3px;
        outline:none; min-width:0;
    }
    .cm-phone-field__input::placeholder { color:#94a3b8; font-weight:400; }
    .cm-phone-field__hint { display:block; margin-top:5px; color:#6b7a93; font-size:11.5px; }
</style>
<script>
(function () {
    if (window.__cmPhoneInit) { return; }
    window.__cmPhoneInit = true;

    function localDigits(val) {
        var d = String(val || '').replace(/\D/g, '');
        if (d.indexOf('237') === 0) { d = d.slice(3); }
        return d.replace(/^0+/, '').slice(0, 9);
    }

    function formatDisplay(digits) {
        if (!digits) { return '+237 '; }
        var parts = [digits.slice(0, 1)];
        var rest = digits.slice(1);
        while (rest.length) {
            parts.push(rest.slice(0, 2));
            rest = rest.slice(2);
        }
        return '+237 ' + parts.join(' ');
    }

    function syncField(wrap) {
        var display = wrap.querySelector('[data-cm-phone-display]');
        var hidden = wrap.querySelector('[data-cm-phone-hidden]');
        if (!display || !hidden || display.readOnly) { return; }
        var digits = localDigits(display.value);
        hidden.value = digits;
        var formatted = formatDisplay(digits);
        if (display.value !== formatted) {
            display.value = formatted;
        }
    }

    document.addEventListener('input', function (e) {
        if (!e.target.matches('[data-cm-phone-display]')) { return; }
        syncField(e.target.closest('[data-cm-phone]'));
    });

    document.addEventListener('focus', function (e) {
        if (!e.target.matches('[data-cm-phone-display]') || e.target.readOnly) { return; }
        var wrap = e.target.closest('[data-cm-phone]');
        var hidden = wrap && wrap.querySelector('[data-cm-phone-hidden]');
        if (hidden && !hidden.value) {
            e.target.value = '+237 ';
        }
    }, true);
})();
</script>
@endif
