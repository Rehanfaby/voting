@extends('frontend.layout.main')
@section('content')

    <main class="mg-tickets">
        <section class="mg-tickets__hero pt-130 pb-50">
            <div class="container text-center">
                <span class="mg-tickets__badge">{{ trans('file.Events') }}</span>
                <h1 class="mg-tickets__title">{{ trans('file.Event List') }}</h1>
                <p class="mg-tickets__lead">{{ trans('file.Book your seat for upcoming gospel events') }}</p>
                <div class="mg-tickets__search-wrap">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="event-search" placeholder="{{ trans('file.Search events') }}…">
                </div>
            </div>
        </section>

        <section class="mg-tickets__grid pb-130">
            <div class="container">
                <div class="row g-4" id="event-list">
                    @foreach($events as $event)
                    <div class="col-xl-4 col-md-6 event-card">
                        <div class="mg-event-card-wrap">
                            <a href="{{ route('tickets', $event->id) }}" class="mg-event-card">
                                <div class="mg-event-card__img">
                                    @if($event->image)
                                        <img src="{{ url('public/images/category', $event->image) }}" alt="{{ $event->name }}" loading="lazy">
                                    @else
                                        <div class="mg-event-card__placeholder"><i class="fa-solid fa-ticket"></i></div>
                                    @endif
                                </div>
                                <div class="mg-event-card__body">
                                    <h3>{{ $event->name }}</h3>
                                    <span class="mg-event-card__cta">{{ trans('file.View tickets') }} <i class="fa-solid fa-arrow-right"></i></span>
                                </div>
                            </a>
                            @include('partials.event_countdown', ['event' => $event, 'class' => 'mg-event-countdown--card'])
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">{{ $events->links() }}</div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
<script src="{{ asset('public/js/event-countdown.js') }}"></script>
<script>
document.getElementById('event-search').addEventListener('input', function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.event-card').forEach(function (el) {
        el.style.display = el.textContent.toLowerCase().indexOf(q) >= 0 ? '' : 'none';
    });
});
</script>
@endsection
