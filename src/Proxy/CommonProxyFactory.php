<?php

namespace FerOliveira\GoogleCrawler\Proxy;

use FerOliveira\GoogleCrawler\Exception\InvalidUrlException;
use FerOliveira\GoogleCrawler\Proxy\HttpClient\CommonProxyGoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\HttpClient\GoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\CommonProxyGoogleUrlParser;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;

class CommonProxyFactory implements ProxyFactory
{
    private string $endpoint;

    public function __construct(string $endpoint = null)
    {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid CommonProxy endpoint: $endpoint");
        }

        $this->endpoint = $endpoint;
    }

    public function createHttpClient(): GoogleHttpClient
    {
        return new CommonProxyGoogleHttpClient($this->endpoint);
    }

    public function createUrlParser(): GoogleUrlParse
    {
        return new CommonProxyGoogleUrlParser();
    }
}