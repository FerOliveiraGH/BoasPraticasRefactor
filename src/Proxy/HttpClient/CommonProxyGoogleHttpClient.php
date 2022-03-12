<?php

namespace FerOliveira\GoogleCrawler\Proxy\HttpClient;

use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class CommonProxyGoogleHttpClient implements GoogleHttpClient
{
    private ?string $endpoint;

    public function __construct(string $endpoint = null)
    {
        $this->endpoint = $endpoint;
    }

    /** @throws GuzzleException */
    public function getHttpResponse(string $url): ResponseInterface
    {
        if (!filter_var($this->endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid CommonProxy endpoint: $this->endpoint");
        }

        $data = ['u' => $url, 'allowCookies' => 'on'];
        $httpClient = new Client(['cookies' => true, 'verify' => false]);
        return $httpClient->request('POST', $this->endpoint, ['form_params' => $data]);
    }
}