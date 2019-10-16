@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">

                        <h1>Swedish Fit auto-login</h1>
                        <p>Réservez vos cours Swedish Fit en avance.</p>

                        <div class="card-body text-center">

                            <h3 class>Réservations automatiques à venir</h3>
                            @if(\Auth::user()->bookings->count() > 0)
                                <div class="row no-gutters">
                                    <div class="col-2">ID</div>
                                    <div class="col-4">Date</div>
                                    <div class="col-3">Type</div>
                                    <div class="col-3">Lieu</div>
                                    @foreach(\Auth::user()->bookings as $booking)
                                        <div class="col-2">

                                            <form action="/bookings/{{$booking->id}}" method="post">
                                                {{csrf_field()}}
                                                <input type="hidden" name="_method" value="delete">
                                                #{{$booking->swedishfit_id}}

                                                <button class="btn btn-sm btn-danger">
                                                    x
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-4">{{$booking->will_be_booked_at->formatLocalized("%d %b %Y")}} {{$booking->details['time']}}</div>
                                        <div class="col-3">{{$booking->details['type']}}</div>
                                        <div class="col-3">
                                            <small>{{$booking->details['location']}}</small>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <small class="text-muted">Vous n'avez aucune réservation à venir.</small>
                            @endif
                        </div>
                        <div class="card-body text-center" id="bookSession">
                            <book-session></book-session>
                        </div>
                        @push('scripts')
                            <script>
                                var bookSession = new Vue({
                                    el: '#bookSession',
                                })
                            </script>
                        @endpush
                    </div>
                </div>
            </div>
@endsection
