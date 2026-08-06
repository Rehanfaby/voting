@php
    use App\Employee;
    use App\Helpers\ImageOptimizer;
    $candidates = Employee::where('is_active', true)
        ->where('is_approve', true)
        ->orderBy('name')
        ->get(['id', 'name', 'image']);
@endphp
@if($candidates->isNotEmpty())
    <h6>All candidates to grade ({{ $candidates->count() }})</h6>
    <p class="text-muted small mb-2">Open Awaiting Candidate, then grade each person below once. Tap a photo to open their public profile.</p>
    <div class="mg-help-candidates">
        @foreach($candidates as $c)
            <a class="mg-help-candidate" href="{{ route('musician.data', $c->id) }}" target="_blank" rel="noopener" title="{{ $c->name }}">
                <img src="{{ ImageOptimizer::employeeImageUrl($c->image) }}" alt="{{ $c->name }}" loading="lazy" width="96" height="96">
                <span>{{ $c->name }}</span>
            </a>
        @endforeach
    </div>
@endif
