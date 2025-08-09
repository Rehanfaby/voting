@csrf
<div class="row">
    @if(isset($point))
        {{-- Edit Mode --}}
        <div class="col-md-6">
            <div class="form-group">
                <h3>Judge: {{ $point->judge->name }}</h3>

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
            <label for="judge_id">Judge</label>
            <select name="judge_id" id="judge_id" class="form-control" required data-live-search="true">
                <option value="">Choose</option>
                @foreach($judges as $j)
                    <option value="{{ $j->id }}" {{ old('judge_id', $point->judge_id ?? '') == $j->id ? 'selected' : '' }}>
                        {{ $j->name }}
                    </option>
                @endforeach
            </select>
            @error('judge_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="candidate_id">Candidate</label>
            <select name="candidate_id" id="candidate_id" class="form-control" required data-live-search="true">
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

@php
    $fields = [
        ['key'=>'depth','label'=>'Profondeur et atmosphere (0-20)'],
        ['key'=>'diction','label'=>'Diction et articulation (0-10)'],
        ['key'=>'accuracy','label'=>'Justesse et precision (0-10)'],
        ['key'=>'interpretation','label'=>'Interpretation, emotion (0-10)'],
        ['key'=>'technique','label'=>'Technique vocale (0-10)'],
        ['key'=>'stage_presence','label'=>'Presentation scénique (0-10)'],
        ['key'=>'song_choice','label'=>'Choix de la chanson (0-10)'],
        ['key'=>'overall_presentation','label'=>'Presentation generale (0-10)'],
        ['key'=>'adaptability','label'=>'Adaptabilite (0-5)'],
        ['key'=>'audience_interaction','label'=>'Interaction du public (0-5)'],
    ];
@endphp

@foreach($fields as $f)
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="{{ $f['key'] }}">{{ $f['label'] }}</label>
            <input
                type="number"
                id="{{ $f['key'] }}"
                name="{{ $f['key'] }}"
                class="form-control"
                value="{{ old($f['key'], $point->{$f['key']} ?? '') }}"
                required>
            @error($f['key'])
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
@endforeach

<div class="col-md-12">
    <div class="form-group mt-3">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
</div>
