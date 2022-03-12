<?php

namespace FerOliveira\GoogleCrawler\Proxy;

use FerOliveira\GoogleCrawler\Proxy\HttpClient\GoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\HttpClient\NoProxyGoogleHttpClient;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\GoogleUrlParse;
use FerOliveira\GoogleCrawler\Proxy\UrlParser\NoProxyGoogleUrlParser;

class NoProxyFactory implements ProxyFactory
{
    private ?string $endpoint;

    public function __construct(string $endpoint = null)
    {
        $this->endpoint = $endpoint;
    }

    public function createHttpClient(): GoogleHttpClient
    {
        return new NoProxyGoogleHttpClient($this->endpoint);
    }

    public function createUrlParser(): GoogleUrlParse
    {
        return new NoProxyGoogleUrlParser();
    }
}