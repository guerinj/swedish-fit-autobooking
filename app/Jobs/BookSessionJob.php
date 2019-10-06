<?php


namespace App\Jobs;


use App\User;
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

class BookSessionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var string
     */
    public $courseId;
    /**
     * @var User
     */
    public $user;

    private $sessionId;

    const SF_POST_LOGIN = 'https://www.swedishfit.fr/lo/';
    const SF_GET_BOOKING = 'https://www.swedishfit.fr/resa/bk/?id=';
    const SF_GET_COURSE_DETAILS = 'https://www.swedishfit.fr/cours/detail/?id=';

    /**
     * BookSessionJob constructor.
     *
     * @param User   $user
     * @param string $sessionId
     */
    public function __construct( User $user, string $courseId )
    {
        $this->user = $user;
        $this->courseId = $courseId;

    }

    /**
     * Executes the job
     */
    public function handle( Client $client )
    {
        $jar = new CookieJar();
        /** @var Response $response */
        $response = $client->post( self::SF_POST_LOGIN, [
            RequestOptions::FORM_PARAMS => [
                'em' => $this->user->email,
                'pw' => $this->user->sf_password
            ],
            RequestOptions::COOKIES     => $jar
        ] );


        /** @var Response $response */
        $response = $client->get( self::SF_GET_COURSE_DETAILS . $this->courseId, [
            RequestOptions::COOKIES => $jar
        ] );

        $dom = new DomDocument();
        libxml_use_internal_errors( true );
        $dom->loadHTML( $response->getBody() );
        libxml_use_internal_errors( false );

        $finder = new DomXPath( $dom );
        /** @var DOMNodeList $nodes */
        $nodes = $finder->query( "//*[contains(@class, 'js-btn-resa-confirm')]" );

        if ( $nodes->count() !== 1 ) {
            throw new \Exception( BookSessionJob::class . ' - Cannot determine the course booking url' );
        }

        $bookingUrl = $nodes->item( 0 )->attributes->getNamedItem( "href" )->value;

        /** @var Response $response */
        $response = $client->get( $bookingUrl, [
            RequestOptions::COOKIES => $jar
        ] );

        $body = $response->getBody();

        return Str::contains( $body, "Votre r&eacute;servation de cours a bien &eacute;t&eacute; enregistr&eacute;e.");
    }
}