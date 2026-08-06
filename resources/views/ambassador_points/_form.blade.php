@csrf
<div class="row">
    @if(isset($point))
        {{-- Edit Mode --}}
        <div class="col-md-6">
            <div class="form-group">
                <h3>Judge: {{ $point->ambassador->name }}</h3>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <h3>Contestant: {{ $point->contestant->name }}</h3>
            </div>
        </div>
    @else
    <div class="col-md-6">
        <div class="form-group mb-3">
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
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="candidate_id">Candidate</label>
            <select name="candidate_id" id="candidate_id" class="form-control" required data-live-search="true">
                @if($candidate_id)
                    <option value="{{ $candidate_id }}"  selected>{{ $candidate_name }}</option>
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
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="points">Points <span class="text-muted">(5 mas)</span></label>
            <input type="number" id="points" name="points"
                class="form-control {{ $errors->has('points') ? 'is-invalid' : '' }}"
                value="{{ old('points', $point->points ?? '') }}"
                min="1" max="5" step="1" required
                inputmode="numeric">
            @error('points')
                <div class="invalid-feedback d-block font-weight-bold">{{ $message }}</div>
            @else
                <small class="form-text text-muted">Maximum grade is 5. Higher values will be rejected.</small>
            @enderror
        </div>
    </div>

<div class="col-md-12">
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary" id="amb-point-save">Save</button>
    </div>
</div>
</div>
<script>
(function () {
    var form = document.getElementById('points') && document.getElementById('points').form;
    var input = document.getElementById('points');
    if (!form || !input) return;
    form.addEventListener('submit', function (e) {
        var v = parseInt(input.value, 10);
        if (isNaN(v) || v < 1 || v > 5) {
            e.preventDefault();
            var msg = 'Points cannot be more than 5. Please enter a number from 1 to 5.';
            if (isNaN(v) || v < 1) msg = 'Points must be at least 1 and at most 5.';
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
            input.classList.add('is-invalid');
            input.focus();
            try { box.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (err) {}
        }
    });
})();
</script>
