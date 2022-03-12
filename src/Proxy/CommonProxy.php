<?php
namespace FerOliveira\GoogleCrawler\Proxy;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class that can be used for multiple proxies services, including hideproxy.me, onlinecollege.info, zend2, etc.
 *
 * @package FerOliveira\GoogleCrawler\Proxy
 * @author Fernando Oliveira
 */
class CommonProxy implements GoogleProxyInterface
{
    /** @var string $endpoint */
    protected $endpoint;

    /**
     * Constructor that initializes the specific proxy service
     *
     * @param string $endpoint Specific service URL
     * @throws InvalidUrlException
     */
    public function __construct(string $endpoint)
    {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid CommonProxy endpoint: $endpoint");
        }

        $this->endpoint = $endpoint;
    }

    /** {@inheritdoc} */
    public function getHttpResponse(string $url): ResponseInterface
    {
        $data = ['u' => $url, 'allowCookies' => 'on'];
        $httpClient = new Client(['cookies' => true, 'verify' => false]);
        $response = $httpClient->request('POST', $this->endpoint, ['form_params' => $data]);

        return $response;
    }

    /** {@inheritdoc} */
    public function parseUrl(string $googleUrl): string
    {
        $link = parse_url($googleUrl);
        parse_str($link['query'], $link);

        parse_str($link['u'], $link);
        $link = array_values($link);

        $googleUrl = filter_var($link[0], FILTER_VALIDATE_URL);
        // If this is not a valid URL, so the result is (probably) an image, news or video suggestion
        if (!$googleUrl) {
            throw new InvalidResultException();
        }

        return $googleUrl;
    }
}
