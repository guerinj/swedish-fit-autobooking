<?php

namespace App\Console\Commands;

use App\Booking;
use App\Jobs\BookSessionJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TriggerBookSessionJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:trigger-bookings {--pretend}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger all booking jobs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        Booking::where( 'will_be_booked_at', '<', Carbon::now() )
               ->each( function ( Booking $booking ) {
                   if ( $this->option( 'pretend' ) ) {
                       $this->line( "Would trigger booking job - User {$booking->user->email} - Session # {$booking->swedishfit_id}" );
                   } else {
                       dispatch( new BookSessionJob( $booking) );
                   }
               } );
    }
}
