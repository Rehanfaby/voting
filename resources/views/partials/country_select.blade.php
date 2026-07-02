@php $countryOptions = \App\Helpers\CountryFlag::options(); @endphp
<select name="country" class="form-control country-select">
    <option value="">{{ trans('file.Country') }}…</option>
    @foreach($countryOptions as $code => $label)
        <option value="{{ $code }}" {{ (isset($selected) && strtoupper((string)$selected) === $code) ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
