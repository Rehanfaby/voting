@csrf
@php
    $fields = [
        [
            'key' => 'accuracy',
            'label' => __('file.Accuracy and precision'),
            'max' => 30,
            'hint' => 'Intonation, rhythm, diction, vocal technique',
        ],
        [
            'key' => 'song_choice',
            'label' => __('file.Choice of song / Key'),
            'max' => 10,
            'hint' => 'Song choice, key & microphone technique',
        ],
        [
            'key' => 'depth',
            'label' => __('file.Depth and atmosphere / Spiritual impact'),
            'max' => 20,
            'hint' => 'Depth, atmosphere & spiritual impact',
        ],
        [
            'key' => 'interpretation',
            'label' => __('file.Interpretation, emotion, and heartfelt engagement / Originality and style'),
            'max' => 20,
            'hint' => 'Emotion, originality & style',
        ],
        [
            'key' => 'overall_presentation',
            'label' => __('file.Overall presentation'),
            'max' => 20,
            'hint' => 'General presentation on stage',
        ],
    ];
@endphp

<div class="mg-grade">
    @if(isset($point))
        <div class="mg-grade__meta">
            <div class="mg-grade__meta-item">
                <span class="mg-grade__meta-label">Judge</span>
                <strong>{{ $point->judge->name }}</strong>
                <input value="{{ $point->candidate_id }}" name="candidate_id" type="hidden">
                <input value="{{ $point->judge_id }}" name="judge_id" type="hidden">
            </div>
            <div class="mg-grade__meta-item">
                <span class="mg-grade__meta-label">Contestant</span>
                <strong>{{ $point->contestant->name }}</strong>
            </div>
        </div>
    @else
        <div class="mg-grade__meta mg-grade__meta--selects">
            <div class="mg-grade__field">
                <label for="judge_id">Judge</label>
                <select name="judge_id" id="judge_id" class="form-control" required data-live-search="true">
                    @if(auth()->user()->role_id == \App\Roles::where('name', 'judge')->where('is_active', true)->first()->id)
                        <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
                    @else
                        <option value="">Choose</option>
                        @foreach($judges as $j)
                            <option value="{{ $j->id }}" {{ old('judge_id', $point->judge_id ?? '') == $j->id ? 'selected' : '' }}>
                                {{ $j->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('judge_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mg-grade__field">
                <label for="candidate_id">Candidate</label>
                <select name="candidate_id" id="candidate_id" class="form-control" required data-live-search="true">
                    @if($candidate_id)
                        <option value="{{ $candidate_id }}" selected>{{ $candidate_name }}</option>
                    @else
                        <option value="">Choose</option>
                        @foreach($candidates as $c)
                            <option value="{{ $c->id }}" {{ old('candidate_id', $point->candidate_id ?? '') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('candidate_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    @endif

    <div class="mg-grade__criteria">
        @foreach($fields as $f)
            <div class="mg-grade__criterion {{ $errors->has($f['key']) ? 'is-invalid' : '' }}">
                <div class="mg-grade__criterion-head">
                    <div>
                        <label for="{{ $f['key'] }}" class="mg-grade__criterion-label">{{ $f['label'] }}</label>
                        <p class="mg-grade__criterion-hint">{{ $f['hint'] }}</p>
                    </div>
                    <span class="mg-grade__max">({{ $f['max'] }}_Max)</span>
                </div>
                <div class="mg-grade__input-wrap">
                    <input
                        type="number"
                        id="{{ $f['key'] }}"
                        name="{{ $f['key'] }}"
                        class="form-control points-input mg-grade__input {{ $errors->has($f['key']) ? 'is-invalid' : '' }}"
                        value="{{ old($f['key'], $point->{$f['key']} ?? '') }}"
                        min="0"
                        max="{{ $f['max'] }}"
                        step="1"
                        inputmode="numeric"
                        data-max="{{ $f['max'] }}"
                        required>
                    <span class="mg-grade__input-cap">/ {{ $f['max'] }}</span>
                </div>
                <div class="mg-grade__field-error" data-error-for="{{ $f['key'] }}">
                    Must be 0–{{ $f['max'] }} ({{ $f['max'] }}_Max)
                </div>
                @error($f['key'])
                    <div class="invalid-feedback d-block font-weight-bold">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="mg-grade__actions">
        <button type="submit" class="btn btn-primary mg-grade__save">Save grade</button>
        <div class="mg-grade__total" aria-live="polite">
            <span class="mg-grade__total-label">{{ trans('file.Total') }}</span>
            <strong class="mg-grade__total-value"><span class="total-points">0</span><small>/100</small></strong>
        </div>
    </div>
</div>

<style>
.mg-grade { max-width: 920px; }
.mg-grade__meta {
    display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 18px;
}
.mg-grade__meta--selects { grid-template-columns: 1fr; }
.mg-grade__meta-item {
    background: #f8fafc; border: 1px solid #e7edf5; border-radius: 14px; padding: 12px 14px;
}
.mg-grade__meta-label {
    display: block; font-size: 11px; font-weight: 700; letter-spacing: .05em;
    text-transform: uppercase; color: #64748b; margin-bottom: 4px;
}
.mg-grade__meta-item strong { color: #0a2350; font-size: 15px; }
.mg-grade__field label {
    font-weight: 700; color: #0a2350; font-size: 13px; margin-bottom: 6px;
}
.mg-grade__criteria {
    display: grid; grid-template-columns: 1fr; gap: 12px;
}
.mg-grade__criterion {
    background: #fff; border: 1px solid #e7edf5; border-radius: 16px; padding: 14px 16px;
    box-shadow: 0 6px 18px rgba(15,23,42,.04);
    transition: border-color .15s ease, box-shadow .15s ease;
}
.mg-grade__criterion:focus-within {
    border-color: #f5c518;
    box-shadow: 0 10px 24px rgba(10,35,80,.1);
}
.mg-grade__criterion.is-invalid {
    border-color: #dc3545;
    background: #fff5f5;
    box-shadow: 0 0 0 2px rgba(220,53,69,.18);
}
.mg-grade__criterion.is-invalid .mg-grade__criterion-label { color: #b91c1c; }
.mg-grade__criterion.is-invalid .mg-grade__max {
    background: #dc3545; color: #fff;
}
.mg-grade__criterion-head {
    display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 10px;
}
.mg-grade__criterion-label {
    margin: 0; font-weight: 800; color: #0a2350; font-size: 14px; line-height: 1.3;
}
.mg-grade__criterion-hint { margin: 4px 0 0; font-size: 12px; color: #64748b; }
.mg-grade__field-error {
    display: none; margin-top: 8px; font-size: 12px; font-weight: 700; color: #b91c1c;
}
.mg-grade__criterion.is-invalid .mg-grade__field-error { display: block; }
.mg-grade__max {
    flex-shrink: 0; display: inline-block; padding: 4px 10px; border-radius: 999px;
    background: #0a2350; color: #f5c518; font-size: 12px; font-weight: 800; letter-spacing: .02em;
}
.mg-grade__input-wrap { position: relative; max-width: 180px; }
.mg-grade__input {
    height: 48px; font-size: 1.25rem; font-weight: 700; color: #0a2350;
    border-radius: 12px; border: 1px solid #cbd5e1; padding-right: 52px;
}
.mg-grade__input.is-invalid,
.mg-grade__criterion.is-invalid .mg-grade__input {
    border-color: #dc3545 !important;
    background: #fff !important;
    color: #b91c1c !important;
    box-shadow: 0 0 0 3px rgba(220,53,69,.2);
}
.mg-grade__input-cap {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #94a3b8; font-weight: 700; font-size: 13px; pointer-events: none;
}
.mg-grade__criterion.is-invalid .mg-grade__input-cap { color: #dc3545; }
.mg-grade__actions {
    display: flex; align-items: center; justify-content: space-between; gap: 14px;
    margin-top: 20px; flex-wrap: wrap;
    padding: 14px 16px; border-radius: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff;
}
.mg-grade__save {
    background: #f5c518 !important; border-color: #f5c518 !important; color: #0a2350 !important;
    font-weight: 800; padding: 10px 22px; border-radius: 999px;
}
.mg-grade__total { text-align: right; }
.mg-grade__total-label {
    display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
    color: rgba(255,255,255,.75); font-weight: 700;
}
.mg-grade__total-value {
    font-size: 1.75rem; line-height: 1.1; color: #f5c518;
}
.mg-grade__total-value small { font-size: .9rem; color: rgba(255,255,255,.7); margin-left: 2px; }
@media (min-width: 768px) {
    .mg-grade__meta, .mg-grade__meta--selects { grid-template-columns: 1fr 1fr; }
    .mg-grade__criteria { grid-template-columns: 1fr 1fr; }
    .mg-grade__criterion:last-child:nth-child(odd) { grid-column: 1 / -1; max-width: calc(50% - 6px); }
}
</style>

<script>
(function () {
    var form = document.querySelector('.mg-grade') && document.querySelector('.mg-grade').closest('form');
    if (!form) return;
    form.setAttribute('novalidate', 'novalidate');

    function markInput(input, invalid) {
        var card = input.closest('.mg-grade__criterion');
        input.classList.toggle('is-invalid', invalid);
        if (card) card.classList.toggle('is-invalid', invalid);
    }

    function validateAll(scrollToFirst) {
        var firstBad = null;
        var overCount = 0;
        form.querySelectorAll('.points-input').forEach(function (input) {
            var max = parseInt(input.getAttribute('data-max'), 10);
            var raw = (input.value || '').trim();
            var v = parseInt(raw, 10);
            var invalid = raw === '' || isNaN(v) || v < 0 || v > max;
            markInput(input, invalid);
            if (invalid && !isNaN(v) && v > max) overCount++;
            if (invalid && !firstBad) firstBad = input;
        });
        return { firstBad: firstBad, overCount: overCount };
    }

    form.querySelectorAll('.points-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var max = parseInt(input.getAttribute('data-max'), 10);
            var raw = (input.value || '').trim();
            if (raw === '') {
                markInput(input, false);
                return;
            }
            var v = parseInt(raw, 10);
            markInput(input, isNaN(v) || v < 0 || v > max);
        });
        input.addEventListener('blur', function () {
            var max = parseInt(input.getAttribute('data-max'), 10);
            var raw = (input.value || '').trim();
            if (raw === '') return;
            var v = parseInt(raw, 10);
            markInput(input, isNaN(v) || v < 0 || v > max);
        });
    });

    form.addEventListener('submit', function (e) {
        var result = validateAll(true);
        if (!result.firstBad) return;
        e.preventDefault();
        var msg = result.overCount > 1
            ? result.overCount + ' scores exceed their Max. Red fields need correcting.'
            : 'Score exceeds Max. Correct the red field(s) below.';
        var box = document.getElementById('mg-grade-client-error');
        if (!box) {
            box = document.createElement('div');
            box.id = 'mg-grade-client-error';
            box.className = 'alert alert-danger alert-validation';
            box.setAttribute('role', 'alert');
            form.parentNode.insertBefore(box, form);
        }
        box.textContent = msg;
        box.style.display = 'block';
        result.firstBad.focus();
        try {
            var card = result.firstBad.closest('.mg-grade__criterion');
            (card || result.firstBad).scrollIntoView({ behavior: 'smooth', block: 'center' });
        } catch (err) {}
    });
})();
</script>
