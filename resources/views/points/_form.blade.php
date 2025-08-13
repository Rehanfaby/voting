@csrf
<div class="row">
    @if(isset($point))
        {{-- Edit Mode --}}
        <div class="col-md-6">
            <div class="form-group">
                <h3>Judge: {{ $point->judge->name }}</h3>
                <input value="{{ $point->candidate_id }}"  name="candidate_id" type="hidden">
                <input value="{{ $point->judge_id }}"  name="judge_id" type="hidden">
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
                @if(auth()->user()->role_id == \App\Roles::where('name', 'judge')->where('is_active', true)->first()->id)
                    <option value="{{ auth()->user()->id }}" }} selected> {{ auth()->user()->name }} </option>
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

@php

    $fields = [
        ['key'=>'accuracy', 'label'=>__('file.Accuracy and precision') . ' - 30'],
        ['key'=>'song_choice', 'label'=>__('file.Choice of song / Key') . ' - 10'],
        ['key'=>'depth', 'label'=>__('file.Depth and atmosphere / Spiritual impact') . ' - 20'],
        ['key'=>'interpretation', 'label'=>__('file.Interpretation, emotion, and heartfelt engagement / Originality and style') . ' - 20'],
        ['key'=>'overall_presentation', 'label'=>__('file.Overall presentation') . ' - 20'],
//        ['key'=>'diction', 'label'=>__('file.Diction and articulation')],
//        ['key'=>'technique', 'label'=>__('file.Vocal technique / Microphone technique')],
//        ['key'=>'stage_presence', 'label'=>__('file.Stage presence / Timing')],
//        ['key'=>'adaptability', 'label'=>__('file.Adaptability and flexibility')],
//        ['key'=>'audience_interaction', 'label'=>__('file.Audience interaction')],
    ];
@endphp

@foreach($fields as $f)
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="{{ $f['key'] }}">{{ $f['label'] }}</label>
            <input
                type="number"
                id="{{ $f['key'] }}"
                name="{{ $f['key'] }}"
                class="form-control points-input"
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
