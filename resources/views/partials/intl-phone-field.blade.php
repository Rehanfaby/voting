@php
    use App\Helpers\PhoneHelper;
    $fieldId = $id ?? 'intl_' . uniqid();
    $fieldName = $name ?? 'phone_intl';
    $fieldLabel = $label ?? trans('file.Whatsapp number');
    $defaultDial = $defaultDial ?? '237';
    [$selectedDial, $localValue] = PhoneHelper::splitIntl($value ?? old($fieldName, ''), $defaultDial);
    $countries = PhoneHelper::countries();
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
            <select class="intl-phone-field__select" data-intl-dial aria-label="{{ trans('file.Country code') }}">
                @foreach($countries as $c)
                    <option value="{{ $c['dial'] }}" data-iso="{{ $c['iso'] }}"
                        {{ $c['dial'] === $selectedDial ? 'selected' : '' }}>
                        {{ $c['flag'] }} {{ $c['name'] }} (+{{ $c['dial'] }})
                    </option>
                @endforeach
            </select>
            <span class="intl-phone-field__dial" data-intl-dial-label>+{{ $selectedDial }}</span>
            <i class="fa fa-chevron-down intl-phone-field__chev" aria-hidden="true"></i>
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

@once
<style>
    .intl-phone-field { margin-bottom: 12px; }
    .intl-phone-field__label { display:block; font-weight:700; color:#0a2350; margin-bottom:6px; font-size:13px; }
    .intl-phone-field__wrap {
        display:flex; align-items:stretch;
        border:1px solid #b8c9e8; border-radius:14px; overflow:hidden;
        background:#fff; box-shadow:0 1px 0 rgba(10,35,80,.04);
        transition:border-color .2s, box-shadow .2s;
    }
    .intl-phone-field__wrap:focus-within { border-color:#1d4ed8; box-shadow:0 0 0 3px rgba(29,78,216,.12); }
    .intl-phone-field__country {
        position:relative; display:flex; align-items:center; gap:6px;
        padding:0 10px; background:linear-gradient(180deg,#eef4ff,#e3ecfb);
        border-right:1px solid #c5d3ea; flex:0 0 auto;
    }
    .intl-phone-field__select {
        position:absolute; inset:0; width:100%; height:100%;
        opacity:0; border:0; cursor:pointer; font-size:16px;
    }
    .intl-phone-field__dial { font-weight:700; font-size:14px; color:#0a2350; pointer-events:none; }
    .intl-phone-field__chev { font-size:10px; color:#64748b; pointer-events:none; }
    .intl-phone-field__input {
        flex:1; border:0; background:transparent; padding:13px 14px;
        font-size:16px; font-weight:500; color:#334155; letter-spacing:.3px;
        outline:none; min-width:0;
    }
    .intl-phone-field__input::placeholder { color:#94a3b8; font-weight:400; }
    .intl-phone-field__hint { display:block; margin-top:5px; color:#6b7a93; font-size:11.5px; }
</style>
<script>
(function () {
    if (window.__intlPhoneInit) { return; }
    window.__intlPhoneInit = true;

    function sync(wrap) {
        var select = wrap.querySelector('[data-intl-dial]');
        var label = wrap.querySelector('[data-intl-dial-label]');
        var local = wrap.querySelector('[data-intl-local]');
        var hidden = wrap.querySelector('[data-intl-hidden]');
        if (!select || !local || !hidden) { return; }
        var dial = String(select.value || '').replace(/\D/g, '');
        var digits = String(local.value || '').replace(/\D/g, '').replace(/^0+/, '');
        if (label) { label.textContent = '+' + dial; }
        hidden.value = digits ? ('+' + dial + digits) : '';
    }

    document.addEventListener('change', function (e) {
        if (!e.target.matches('[data-intl-dial]')) { return; }
        sync(e.target.closest('[data-intl-phone]'));
    });
    document.addEventListener('input', function (e) {
        if (!e.target.matches('[data-intl-local]')) { return; }
        sync(e.target.closest('[data-intl-phone]'));
    });

    document.querySelectorAll('[data-intl-phone]').forEach(sync);
})();
</script>
@endonce
