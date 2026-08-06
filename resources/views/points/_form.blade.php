@csrf
@php
    $fields = [
        ['key' => 'accuracy', 'label' => __('file.Accuracy and precision'), 'max' => 30],
        ['key' => 'song_choice', 'label' => __('file.Choice of song / Key'), 'max' => 10],
        ['key' => 'depth', 'label' => __('file.Depth and atmosphere / Spiritual impact'), 'max' => 20],
        ['key' => 'interpretation', 'label' => __('file.Interpretation, emotion, and heartfelt engagement / Originality and style'), 'max' => 20],
        ['key' => 'overall_presentation', 'label' => __('file.Overall presentation'), 'max' => 20],
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
            <div class="mg-grade__criterion {{ $errors->has($f['key']) ? 'is-invalid' : '' }}" data-max="{{ $f['max'] }}">
                <div class="mg-grade__criterion-head">
                    <label for="{{ $f['key'] }}" class="mg-grade__criterion-label">{{ $f['label'] }}</label>
                    <span class="mg-grade__max">{{ $f['max'] }}_Max</span>
                </div>
                <div class="mg-grade__bar" aria-hidden="true">
                    <div class="mg-grade__bar-fill" data-bar-for="{{ $f['key'] }}" style="width: 0%;"></div>
                </div>
                <div class="mg-grade__score-row">
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
                    <span class="mg-grade__pct" data-pct-for="{{ $f['key'] }}">0%</span>
                </div>
                <div class="mg-grade__field-error" data-error-for="{{ $f['key'] }}">
                    Maximum allowed score is {{ $f['max'] }}
                </div>
                @error($f['key'])
                    <div class="invalid-feedback d-block font-weight-bold">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>

    <div class="mg-grade__actions">
        <div class="mg-grade__total-block">
            <div class="mg-grade__total" aria-live="polite">
                <span class="mg-grade__total-label">{{ trans('file.Total') }}</span>
                <strong class="mg-grade__total-value"><span class="total-points">0</span><small>/100</small></strong>
            </div>
            <div class="mg-grade__total-bar" aria-hidden="true">
                <div class="mg-grade__total-bar-fill" id="mg-grade-total-bar" style="width: 0%;"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mg-grade__save">Save grade</button>
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
    margin: 0; font-weight: 800; color: #0a2350; font-size: 14px; line-height: 1.35;
    flex: 1; min-width: 0;
}
.mg-grade__bar {
    height: 8px; border-radius: 999px; background: #e8edf5; overflow: hidden; margin-bottom: 12px;
}
.mg-grade__bar-fill {
    height: 100%; width: 0; border-radius: 999px;
    background: #dc2626;
    transition: width .22s ease, background-color .25s ease;
}
.mg-grade__score-row {
    display: flex; align-items: center; gap: 12px;
}
.mg-grade__pct {
    min-width: 44px; text-align: right; font-size: 12px; font-weight: 800; color: #64748b;
}
.mg-grade__criterion.is-invalid .mg-grade__pct { color: #dc2626; }
.mg-grade__field-error {
    display: none; margin-top: 8px; font-size: 12px; font-weight: 700; color: #b91c1c;
}
.mg-grade__criterion.is-invalid .mg-grade__field-error { display: block; }
.mg-grade__max {
    flex-shrink: 0; display: inline-block; padding: 4px 10px; border-radius: 999px;
    background: #0a2350; color: #f5c518; font-size: 12px; font-weight: 800; letter-spacing: .02em;
    white-space: nowrap;
}
.mg-grade__input-wrap { position: relative; flex: 1; max-width: 180px; }
.mg-grade__input {
    height: 48px; width: 100%; font-size: 1.25rem; font-weight: 700; color: #0a2350;
    border-radius: 12px; border: 1px solid #cbd5e1; padding-right: 52px;
    -moz-appearance: textfield;
}
.mg-grade__input::-webkit-outer-spin-button,
.mg-grade__input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
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
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    margin-top: 20px; flex-wrap: wrap;
    padding: 16px 18px; border-radius: 16px;
    background: linear-gradient(135deg, #0a2350 0%, #1d4ed8 100%);
    color: #fff;
}
.mg-grade__total-block { flex: 1 1 220px; min-width: 0; }
.mg-grade__total { margin-bottom: 8px; }
.mg-grade__total-label {
    display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
    color: rgba(255,255,255,.75); font-weight: 700;
}
.mg-grade__total-value {
    font-size: 1.75rem; line-height: 1.1; color: #f5c518;
}
.mg-grade__total-value small { font-size: .9rem; color: rgba(255,255,255,.7); margin-left: 2px; }
.mg-grade__total-bar {
    height: 10px; border-radius: 999px; background: rgba(255,255,255,.18); overflow: hidden;
}
.mg-grade__total-bar-fill {
    height: 100%; width: 0; border-radius: 999px;
    background: #dc2626;
    box-shadow: 0 0 12px rgba(220,38,38,.35);
    transition: width .22s ease, background-color .25s ease, box-shadow .25s ease;
}
.mg-grade__save {
    background: #f5c518 !important; border-color: #f5c518 !important; color: #0a2350 !important;
    font-weight: 800; padding: 12px 22px; border-radius: 999px; min-height: 44px; flex-shrink: 0;
}
@media (max-width: 767.98px) {
    .mg-grade { max-width: 100%; width: 100%; box-sizing: border-box; }
    .mg-grade__criterion { padding: 12px; border-radius: 14px; }
    .mg-grade__criterion-head { gap: 8px; margin-bottom: 8px; }
    .mg-grade__criterion-label { font-size: 13px; }
    .mg-grade__max { font-size: 11px; padding: 3px 8px; }
    .mg-grade__score-row { gap: 8px; }
    .mg-grade__input-wrap { max-width: none; flex: 1 1 auto; min-width: 0; }
    .mg-grade__input { height: 52px; font-size: 1.35rem; }
    .mg-grade__pct { min-width: 40px; font-size: 11px; }
    .mg-grade__actions {
        flex-direction: column; align-items: stretch;
        position: sticky; bottom: 0; z-index: 5;
        margin-top: 14px;
    }
    .mg-grade__save { width: 100%; order: 2; }
    .mg-grade__total-block { order: 1; }
    .mg-grade__total-value { font-size: 1.45rem; }
}
@media (min-width: 992px) {
    .mg-grade__meta, .mg-grade__meta--selects { grid-template-columns: 1fr 1fr; }
    .mg-grade__criteria { grid-template-columns: 1fr 1fr; }
    .mg-grade__criterion:last-child:nth-child(odd) { grid-column: 1 / -1; max-width: calc(50% - 6px); }
}
</style>

<script>
(function () {
    var root = document.querySelector('.mg-grade');
    var form = root && root.closest('form');
    if (!form || !root) return;
    form.setAttribute('novalidate', 'novalidate');

    var totalSpan = root.querySelector('.total-points');
    var totalBar = document.getElementById('mg-grade-total-bar');

    function markInput(input, invalid) {
        var card = input.closest('.mg-grade__criterion');
        input.classList.toggle('is-invalid', invalid);
        if (card) card.classList.toggle('is-invalid', invalid);
    }

    /** Under half = red; from half to full shifts amber → green. Over max stays red. */
    function scoreBarColor(ratio, over) {
        if (over) return '#dc2626';
        ratio = Math.max(0, Math.min(1, ratio || 0));
        if (ratio <= 0) return '#dc2626';
        if (ratio < 0.5) {
            return '#dc2626';
        }
        var t = (ratio - 0.5) / 0.5;
        var hue = Math.round(8 + t * (142 - 8));
        var sat = Math.round(82 - t * 10);
        var light = Math.round(44 + t * 4);
        return 'hsl(' + hue + ', ' + sat + '%, ' + light + '%)';
    }

    function applyBarColor(el, ratio, over) {
        if (!el) return;
        var color = scoreBarColor(ratio, over);
        el.style.background = color;
        if (color.indexOf('hsl(') === 0) {
            el.style.boxShadow = '0 0 10px ' + color.replace('hsl(', 'hsla(').replace(')', ', 0.35)');
        } else {
            el.style.boxShadow = '0 0 10px rgba(220,38,38,.35)';
        }
    }

    function refreshScores() {
        var total = 0;
        var anyOver = false;
        form.querySelectorAll('.points-input').forEach(function (input) {
            var max = parseInt(input.getAttribute('data-max'), 10) || 0;
            var raw = (input.value || '').trim();
            var v = parseInt(raw, 10);
            var validNum = raw !== '' && !isNaN(v);
            var score = validNum ? Math.max(0, v) : 0;
            var over = validNum && v > max;
            var capped = Math.min(score, max);
            var ratio = max > 0 ? capped / max : 0;
            var pct = ratio * 100;
            if (over) {
                pct = 100;
                anyOver = true;
            }

            var bar = root.querySelector('[data-bar-for="' + input.id + '"]');
            var pctEl = root.querySelector('[data-pct-for="' + input.id + '"]');
            if (bar) {
                bar.style.width = (validNum ? pct : 0) + '%';
                applyBarColor(bar, over ? 0 : ratio, over);
            }
            if (pctEl) {
                if (over) {
                    pctEl.textContent = 'Max!';
                    pctEl.style.color = '#dc2626';
                } else if (validNum) {
                    pctEl.textContent = Math.round(ratio * 100) + '%';
                    pctEl.style.color = scoreBarColor(ratio, false);
                } else {
                    pctEl.textContent = '0%';
                    pctEl.style.color = '#64748b';
                }
            }

            if (validNum) total += v;
        });

        if (totalSpan) totalSpan.textContent = total;
        if (totalBar) {
            var totalRatio = Math.max(0, Math.min(1, total / 100));
            totalBar.style.width = (Math.min(100, Math.max(0, total))) + '%';
            applyBarColor(totalBar, anyOver || total > 100 ? 0 : totalRatio, anyOver || total > 100);
        }
    }

    function validateAll() {
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
        refreshScores();
        return { firstBad: firstBad, overCount: overCount };
    }

    form.querySelectorAll('.points-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var max = parseInt(input.getAttribute('data-max'), 10);
            var raw = (input.value || '').trim();
            if (raw === '') {
                markInput(input, false);
            } else {
                var v = parseInt(raw, 10);
                markInput(input, isNaN(v) || v < 0 || v > max);
            }
            refreshScores();
        });
        input.addEventListener('blur', function () {
            var max = parseInt(input.getAttribute('data-max'), 10);
            var raw = (input.value || '').trim();
            if (raw === '') return;
            var v = parseInt(raw, 10);
            markInput(input, isNaN(v) || v < 0 || v > max);
            refreshScores();
        });
    });

    form.addEventListener('submit', function (e) {
        var result = validateAll();
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

    refreshScores();
    window.mgGradeRefreshScores = refreshScores;
})();
</script>
