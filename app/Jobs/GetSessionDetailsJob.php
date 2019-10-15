<?php


namespace App\Jobs;


use DOMDocument;
use DOMNodeList;
use DOMXPath;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Str;

class GetSessionDetailsJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var string
     */
    public $courseId;


    private $sessionId;


    const SF_GET_COURSE_DETAILS = 'https://www.swedishfit.fr/cours/detail/?id=';

    /**
     * BookSessionJob constructor.
     *
     * @param User   $user
     * @param string $sessionId
     */
    public function __construct( string $courseId )
    {

        $this->courseId = $courseId;

    }

    /**
     * Executes the job
     */
    public function handle( Client $client )
    {

        /** @var Response $response */
        $response = $client->get( self::SF_GET_COURSE_DETAILS . $this->courseId );

        $dom = new DomDocument();
        libxml_use_internal_errors( true );
        $dom->loadHTML( $response->getBody() );
        libxml_use_internal_errors( false );

        $finder = new DomXPath( $dom );

        $titleNodes = $finder->query( "//*[contains(@class, '__HeaderTitle')]/h1" );
        if ( $titleNodes->count() !== 1 ) {
            throw new \Exception( GetSessionDetailsJob::class . ' - Cannot determine the session title' );
        }
        $sessionTitle = $titleNodes->item( 0 )->textContent;

        $scheduleNodes = $finder->query( "//*[contains(@class, '__schedule')]//h2" );
        if ( $scheduleNodes->count() !== 1 ) {
            throw new \Exception( GetSessionDetailsJob::class . ' - Cannot determine the session schedule' );
        }
        $sessionSchedule = $scheduleNodes->item( 0 )->textContent;

        $typeNodes = $finder->query( "//*[contains(@class, 'activiteName')]" );
        if ( $typeNodes->count() !== 1 ) {
            throw new \Exception( GetSessionDetailsJob::class . ' - Cannot determine the session type' );
        }
        $sessionType = $typeNodes->item( 0 )->textContent;


        $locationNodes = $finder->query( "//*[contains(@class, 'DetailSalle__column2')]//h2" );
        if ( $locationNodes->count() !== 1 ) {
            throw new \Exception( GetSessionDetailsJob::class . ' - Cannot determine the session location' );
        }
        $sessionLocation = $locationNodes->item( 0 )->textContent;


        return [
            'date'     => $sessionTitle,
            'time'     => $sessionSchedule,
            'type'     => $sessionType,
            'location' => $sessionLocation
        ];
    }
}