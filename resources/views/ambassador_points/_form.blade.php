@csrf
@php
    $maxPoints = 5;
@endphp

<div class="mg-grade mg-amb-grade">
    @if(isset($point))
        <div class="mg-grade__meta">
            <div class="mg-grade__meta-item">
                <span class="mg-grade__meta-label">Ambassador</span>
                <strong>{{ $point->ambassador->name }}</strong>
            </div>
            <div class="mg-grade__meta-item">
                <span class="mg-grade__meta-label">Contestant</span>
                <strong>{{ $point->contestant->name }}</strong>
            </div>
        </div>
    @else
        <div class="mg-grade__meta mg-grade__meta--selects">
            <div class="mg-grade__field">
                <label for="ambassador_id">Ambassadors</label>
                <select name="ambassador_id" id="ambassador_id" class="form-control" required data-live-search="true">
                    @if(auth()->user()->role_id == \App\Roles::where('name', 'ambassador')->where('is_active', true)->first()->id)
                        <option value="{{ auth()->user()->id }}" selected>{{ auth()->user()->name }}</option>
                    @else
                        <option value="">Choose</option>
                        @foreach($ambassadors as $j)
                            <option value="{{ $j->id }}" {{ old('ambassador_id', $point->ambassador_id ?? '') == $j->id ? 'selected' : '' }}>
                                {{ $j->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('ambassador_id')
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

    <div class="mg-grade__criteria mg-amb-grade__criteria">
        <div class="mg-grade__criterion {{ $errors->has('points') ? 'is-invalid' : '' }}" data-max="{{ $maxPoints }}">
            <div class="mg-grade__criterion-head">
                <label for="points" class="mg-grade__criterion-label">Points</label>
                <span class="mg-grade__max">{{ $maxPoints }}_Max</span>
            </div>
            <div class="mg-grade__bar" aria-hidden="true">
                <div class="mg-grade__bar-fill" data-bar-for="points" style="width: 0%;"></div>
            </div>
            <div class="mg-grade__score-row">
                <div class="mg-grade__input-wrap">
                    <input
                        type="number"
                        id="points"
                        name="points"
                        class="form-control points-input mg-grade__input {{ $errors->has('points') ? 'is-invalid' : '' }}"
                        value="{{ old('points', $point->points ?? '') }}"
                        min="1"
                        max="{{ $maxPoints }}"
                        step="1"
                        inputmode="numeric"
                        data-max="{{ $maxPoints }}"
                        data-min="1"
                        required>
                    <span class="mg-grade__input-cap">/ {{ $maxPoints }}</span>
                </div>
                <span class="mg-grade__pct" data-pct-for="points">0%</span>
            </div>
            <div class="mg-grade__field-error">
                Maximum allowed score is {{ $maxPoints }}
            </div>
            @error('points')
                <div class="invalid-feedback d-block font-weight-bold">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mg-grade__actions">
        <div class="mg-grade__total-block">
            <div class="mg-grade__total" aria-live="polite">
                <span class="mg-grade__total-label">{{ trans('file.Total') }}</span>
                <strong class="mg-grade__total-value"><span class="total-points">0</span><small>/{{ $maxPoints }}</small></strong>
            </div>
            <div class="mg-grade__total-bar" aria-hidden="true">
                <div class="mg-grade__total-bar-fill" id="mg-amb-total-bar" style="width: 0%;"></div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mg-grade__save" id="amb-point-save">Save grade</button>
    </div>
</div>

<style>
.mg-amb-grade { max-width: 720px; }
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
.mg-amb-grade__criteria { display: grid; grid-template-columns: 1fr; gap: 12px; }
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
.mg-grade__criterion.is-invalid .mg-grade__max { background: #dc3545; color: #fff; }
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
    height: 100%; width: 0; border-radius: 999px; background: #dc2626;
    transition: width .22s ease, background-color .25s ease;
}
.mg-grade__score-row { display: flex; align-items: center; gap: 12px; }
.mg-grade__pct {
    min-width: 44px; text-align: right; font-size: 12px; font-weight: 800; color: #64748b;
}
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
.mg-grade__input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
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
.mg-grade__total-value { font-size: 1.75rem; line-height: 1.1; color: #f5c518; }
.mg-grade__total-value small { font-size: .9rem; color: rgba(255,255,255,.7); margin-left: 2px; }
.mg-grade__total-bar {
    height: 10px; border-radius: 999px; background: rgba(255,255,255,.18); overflow: hidden;
}
.mg-grade__total-bar-fill {
    height: 100%; width: 0; border-radius: 999px; background: #dc2626;
    box-shadow: 0 0 12px rgba(220,38,38,.35);
    transition: width .22s ease, background-color .25s ease, box-shadow .25s ease;
}
.mg-grade__save {
    background: #f5c518 !important; border-color: #f5c518 !important; color: #0a2350 !important;
    font-weight: 800; padding: 12px 22px; border-radius: 999px; min-height: 44px; flex-shrink: 0;
}
@media (max-width: 767.98px) {
    .mg-grade__criterion { padding: 12px; border-radius: 14px; }
    .mg-grade__input-wrap { max-width: none; }
    .mg-grade__input { height: 52px; font-size: 1.35rem; }
    .mg-grade__actions {
        flex-direction: column; align-items: stretch;
        position: sticky; bottom: 8px; z-index: 5;
    }
    .mg-grade__save { width: 100%; order: 2; }
    .mg-grade__total-block { order: 1; }
}
@media (min-width: 768px) {
    .mg-grade__meta, .mg-grade__meta--selects { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
(function () {
    var root = document.querySelector('.mg-amb-grade');
    var form = root && root.closest('form');
    var input = document.getElementById('points');
    if (!form || !root || !input) return;
    form.setAttribute('novalidate', 'novalidate');

    var max = parseInt(input.getAttribute('data-max'), 10) || 5;
    var min = parseInt(input.getAttribute('data-min'), 10) || 1;
    var totalSpan = root.querySelector('.total-points');
    var totalBar = document.getElementById('mg-amb-total-bar');
    var bar = root.querySelector('[data-bar-for="points"]');
    var pctEl = root.querySelector('[data-pct-for="points"]');

    function scoreBarColor(ratio, over) {
        if (over) return '#dc2626';
        ratio = Math.max(0, Math.min(1, ratio || 0));
        if (ratio <= 0 || ratio < 0.5) return '#dc2626';
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

    function markInvalid(invalid) {
        input.classList.toggle('is-invalid', invalid);
        var card = input.closest('.mg-grade__criterion');
        if (card) card.classList.toggle('is-invalid', invalid);
    }

    function refresh() {
        var raw = (input.value || '').trim();
        var v = parseInt(raw, 10);
        var validNum = raw !== '' && !isNaN(v);
        var over = validNum && v > max;
        var under = validNum && v < min;
        var capped = validNum ? Math.min(Math.max(v, 0), max) : 0;
        var ratio = max > 0 ? capped / max : 0;
        var pct = over ? 100 : ratio * 100;

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
        if (totalSpan) totalSpan.textContent = validNum ? v : 0;
        if (totalBar) {
            totalBar.style.width = (validNum ? Math.min(100, (Math.max(0, v) / max) * 100) : 0) + '%';
            if (over) totalBar.style.width = '100%';
            applyBarColor(totalBar, over ? 0 : ratio, over);
        }
        if (raw === '') markInvalid(false);
        else markInvalid(over || under || isNaN(v));
    }

    input.addEventListener('input', refresh);
    input.addEventListener('blur', refresh);

    form.addEventListener('submit', function (e) {
        var v = parseInt(input.value, 10);
        if (!isNaN(v) && v >= min && v <= max) return;
        e.preventDefault();
        var msg = 'Points cannot be more than ' + max + '. Please enter a number from ' + min + ' to ' + max + '.';
        if (isNaN(v) || v < min) msg = 'Points must be at least ' + min + ' and at most ' + max + '.';
        var box = document.getElementById('amb-points-client-error');
        if (!box) {
            box = document.createElement('div');
            box.id = 'amb-points-client-error';
            box.className = 'alert alert-danger alert-validation';
            box.setAttribute('role', 'alert');
            form.parentNode.insertBefore(box, form);
        }
        box.textContent = msg;
        box.style.display = 'block';
        markInvalid(true);
        refresh();
        input.focus();
        try {
            var card = input.closest('.mg-grade__criterion');
            (card || input).scrollIntoView({ behavior: 'smooth', block: 'center' });
        } catch (err) {}
    });

    refresh();
})();
</script>
