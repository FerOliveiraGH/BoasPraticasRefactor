<?php

namespace FerOliveira\GoogleCrawler\Proxy\HttpClient;

use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class NoProxyGoogleHttpClient implements GoogleHttpClient
{
    private ?string $endpoint;

    public function __construct(string $endpoint = null)
    {
        $this->endpoint = $endpoint;
    }

    /** @throws GuzzleException */
    public function getHttpResponse(string $url): ResponseInterface
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid Google URL: $url - $this->endpoint");
        }

        return (new Client(['verify' => false]))->request('GET', $url);
    }
}