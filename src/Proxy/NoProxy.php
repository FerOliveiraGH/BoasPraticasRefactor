<?php
namespace FerOliveira\GoogleCrawler\Proxy;

use FerOliveira\GoogleCrawler\Exception\InvalidResultException;
use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class that represents the absense of a proxy service, making the direct request to the url
 * and returning its response
 *
 * @package FerOliveira\GoogleCrawler\Proxy
 * @author Fernando Oliveira
 */
class NoProxy implements GoogleProxyInterface
{
    /** {@inheritdoc}
     * @throws GuzzleException
     */
    public function getHttpResponse(string $url): ResponseInterface
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid Google URL: $url");
        }

        return (new Client(['verify' => false]))->request('GET', $url);
    }

    /** {@inheritdoc} */
    public function parseUrl(string $googleUrl): string
    {
        $urlParse = parse_url($googleUrl);
        parse_str($urlParse['query'], $queryStringParams);

        $resultUrl = filter_var($queryStringParams['q'], FILTER_VALIDATE_URL);
        if (!$resultUrl) {
            throw new InvalidResultException();
        }

        return $resultUrl;
    }
}
