<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Jobs\GetSessionDetailsJob;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view( 'home' );
    }

    public function ajaxSessionDetails( Request $request, $sessionId )
    {
        return response()->json( [
            'data'    => ( new GetSessionDetailsJob( $sessionId ) )->handle( new Client() ),
            'message' => __( 'Session details retrieved' )
        ] );
    }

    public function createBooking( Request $request )
    {
        $details = json_decode( $request->input( 'details' ), true );
        $willBeBookedAt = Carbon::createFromTimestamp( $details['timestamp'] );
        unset( $details['timestamp'] );
        $booking = Booking::create( [
            'user_id'           => \Auth::id(),
            'swedishfit_id'     => $request->input( 'swedishfit_id' ),
            'details'           => $details,
            'will_be_booked_at' => $willBeBookedAt,
        ] );

        return redirect( route( 'home' ) );
    }

    public function deleteBooking( Request $request, $bookingId )
    {
        $booking = \Auth::user()->bookings()->findOrFail( $bookingId );

        $booking->delete();

        return redirect( route( 'home' ) );
    }
}
