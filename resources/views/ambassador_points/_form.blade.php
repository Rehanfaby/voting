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
            <select name="ambassador_id" id="ambassador_id" class="form-control" required>
                <option value="">Choose</option>
                @foreach($ambassadors as $j)
                    <option value="{{ $j->id }}" {{ old('ambassador_id', $point->ambassador_id ?? '') == $j->id ? 'selected' : '' }}>
                        {{ $j->name }}
                    </option>
                @endforeach
            </select>
            @error('ambassador_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="candidate_id">Candidate</label>
            <select name="candidate_id" id="candidate_id" class="form-control" required>
                <option value="">Choose</option>
                @foreach($candidates as $c)
                    <option value="{{ $c->id }}" {{ old('candidate_id', $point->candidate_id ?? '') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
            @error('candidate_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    @endif
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="points">Points <sub>max-5</sub></label>
            <input type="number" id="points" name="points"
                class="form-control"
                value="{{ old('points', $point->points ?? '') }}"
                required>
        </div>
    </div>

<div class="col-md-12">
    <div class="form-group mt-3">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
</div>
