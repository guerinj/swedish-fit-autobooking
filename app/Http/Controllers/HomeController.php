<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Jobs\GetSessionDetailsJob;
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
        $booking = Booking::create( [
            'user_id'       => \Auth::id(),
            'swedishfit_id' => $request->input( 'swedishfit_id' ),
            'details'       => json_decode( $request->input( 'details' ) ),
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
